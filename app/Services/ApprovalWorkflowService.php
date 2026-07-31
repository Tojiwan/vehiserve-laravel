<?php

namespace App\Services;

use App\Enums\ApprovalRole;
use App\Enums\TravelRequestStatus;
use App\Enums\VehicleRequestStatus;
use App\Models\Approval;
use App\Models\Booking;
use App\Models\TravelRequest;
use App\Models\VehicleRequest;
use App\Notifications\RequestApproved;
use App\Notifications\RequestRejected;
use App\Notifications\RequestSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApprovalWorkflowService
{
    /**
     * Submit a new vehicle request and initialize approval chain
     */
    public function submitVehicleRequest(VehicleRequest $request): void
    {
        DB::transaction(function () use ($request) {
            // Create initial approval records for each role
            $roles = [ApprovalRole::MOTOR_POOL, ApprovalRole::DEAN, ApprovalRole::VICE_PRESIDENT, ApprovalRole::SUC_PRESIDENT];
            
            foreach ($roles as $index => $role) {
                Approval::create([
                    'approvable_type' => VehicleRequest::class,
                    'approvable_id' => $request->id,
                    'user_ID' => null, // Will be assigned when approver takes action
                    'role' => $role->value,
                    'status' => $index === 0 ? 'Pending' : 'Waiting',
                    'approved_at' => null,
                ]);
            }

            // Update request status
            $request->update(['vehicle_status' => VehicleRequestStatus::PENDING_MOTOR_POOL->value]);

            // Notify Motor Pool staff
            $this->notifyApprovers($request, ApprovalRole::MOTOR_POOL);
        });
    }

    /**
     * Submit a new travel request and initialize approval chain
     */
    public function submitTravelRequest(TravelRequest $request): void
    {
        DB::transaction(function () use ($request) {
            // Create initial approval records for each role (Dean -> VP -> SUC -> Motor Pool)
            $roles = [ApprovalRole::DEAN, ApprovalRole::VICE_PRESIDENT, ApprovalRole::SUC_PRESIDENT, ApprovalRole::MOTOR_POOL];
            
            foreach ($roles as $index => $role) {
                Approval::create([
                    'approvable_type' => TravelRequest::class,
                    'approvable_id' => $request->id,
                    'user_ID' => null,
                    'role' => $role->value,
                    'status' => $index === 0 ? 'Pending' : 'Waiting',
                    'approved_at' => null,
                ]);
            }

            // Update request status
            $request->update(['vehicle_status' => TravelRequestStatus::PENDING_DEAN->value]);

            // Notify Dean
            $this->notifyApprovers($request, ApprovalRole::DEAN);
        });
    }

    /**
     * Process an approval action
     */
    public function processApproval(Approval $approval, bool $approved, ?string $comment = null, ?string $signature = null): void
    {
        DB::transaction(function () use ($approval, $approved, $comment, $signature) {
            $approval->update([
                'user_ID' => auth()->id(),
                'status' => $approved ? 'Approved' : 'Rejected',
                'comment' => $comment,
                'signature' => $signature,
                'approved_at' => now(),
            ]);

            $request = $approval->approvable;

            if ($request instanceof VehicleRequest) {
                $this->processVehicleRequestApproval($request, $approval, $approved);
            } elseif ($request instanceof TravelRequest) {
                $this->processTravelRequestApproval($request, $approval, $approved);
            }
        });
    }

    /**
     * Process vehicle request approval workflow
     */
    protected function processVehicleRequestApproval(VehicleRequest $request, Approval $currentApproval, bool $approved): void
    {
        if (!$approved) {
            $this->rejectRequest($request, $currentApproval->role, $currentApproval->comment);
            return;
        }

        $currentStatus = VehicleRequestStatus::tryFrom($request->vehicle_status);
        $nextStatus = $currentStatus?->nextStatus(true);

        if ($nextStatus) {
            $request->update(['vehicle_status' => $nextStatus->value]);
        }

        // Find next pending approval
        $nextApproval = $request->approvals()
            ->where('status', 'Waiting')
            ->orderByRaw("CASE role 
                WHEN 'Motor Pool' THEN 1 
                WHEN 'Dean' THEN 2 
                WHEN 'Vice President' THEN 3 
                WHEN 'SUC President' THEN 4 
                ELSE 5 END")
            ->first();

        if ($nextApproval) {
            $nextApproval->update(['status' => 'Pending']);
            $this->notifyApprovers($request, ApprovalRole::tryFrom($nextApproval->role));
        } else {
            // All approvals complete - create booking if vehicle assigned
            $this->completeVehicleRequest($request);
        }
    }

    /**
     * Process travel request approval workflow
     */
    protected function processTravelRequestApproval(TravelRequest $request, Approval $currentApproval, bool $approved): void
    {
        if (!$approved) {
            $this->rejectRequest($request, $currentApproval->role, $currentApproval->comment);
            return;
        }

        $currentStatus = TravelRequestStatus::tryFrom($request->vehicle_status);
        $nextStatus = $currentStatus?->nextStatus(true);

        if ($nextStatus) {
            $request->update(['vehicle_status' => $nextStatus->value]);
        }

        // Find next pending approval
        $nextApproval = $request->approvals()
            ->where('status', 'Waiting')
            ->orderByRaw("CASE role 
                WHEN 'Dean' THEN 1 
                WHEN 'Vice President' THEN 2 
                WHEN 'SUC President' THEN 3 
                WHEN 'Motor Pool' THEN 4 
                ELSE 5 END")
            ->first();

        if ($nextApproval) {
            $nextApproval->update(['status' => 'Pending']);
            $this->notifyApprovers($request, ApprovalRole::tryFrom($nextApproval->role));
        } else {
            // All approvals complete
            $this->completeTravelRequest($request);
        }
    }

    /**
     * Reject a request and notify user
     */
    protected function rejectRequest($request, string $role, ?string $comment): void
    {
        $statusField = $request instanceof VehicleRequest ? 'vehicle_status' : 'vehicle_status';
        $rejectedStatus = $request instanceof VehicleRequest 
            ? VehicleRequestStatus::REJECTED->value 
            : TravelRequestStatus::REJECTED->value;

        $request->update([$statusField => $rejectedStatus]);
        
        // Mark remaining approvals as cancelled
        $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);

        // Notify user
        $request->user->notify(new RequestRejected($request, $role, $comment));
    }

    /**
     * Complete vehicle request - create booking if vehicle assigned
     */
    protected function completeVehicleRequest(VehicleRequest $request): void
    {
        $request->update(['vehicle_status' => VehicleRequestStatus::COMPLETED->value]);

        // Check if vehicle and driver are assigned (via latest Motor Pool approval)
        $mpApproval = $request->approvals()->where('role', 'Motor Pool')->latest()->first();
        
        if ($mpApproval && $mpApproval->status === 'Approved') {
            // Booking creation would happen here when MP assigns vehicle/driver
            // This is typically done in the Motor Pool approval UI
        }

        $request->user->notify(new RequestApproved($request));
    }

    /**
     * Complete travel request
     */
    protected function completeTravelRequest(TravelRequest $request): void
    {
        $request->update(['vehicle_status' => TravelRequestStatus::COMPLETED->value]);
        $request->user->notify(new RequestApproved($request));
    }

    /**
     * Notify approvers of pending request
     */
    protected function notifyApprovers($request, ?ApprovalRole $role): void
    {
        if (!$role) return;

        $permission = $request instanceof VehicleRequest 
            ? $role->permission() 
            : $role->travelPermission();

        $approvers = \App\Models\User::role($role->value)->get();
        
        foreach ($approvers as $approver) {
            if ($approver->can($permission)) {
                $approver->notify(new RequestSubmitted($request, $role));
            }
        }
    }

    /**
     * Assign vehicle and driver to approved request
     */
    public function assignVehicleAndDriver(VehicleRequest $request, int $vehicleId, int $driverId): void
    {
        DB::transaction(function () use ($request, $vehicleId, $driverId) {
            $vehicle = \App\Models\Vehicle::findOrFail($vehicleId);
            $driver = \App\Models\Driver::findOrFail($driverId);

            // Update vehicle and driver status
            $vehicle->update(['status' => 'On Trip']);
            $driver->update(['status' => 'On Trip']);

            // Create booking record
            Booking::create([
                'user_ID' => $request->user_ID,
                'requesting_personnel' => $request->requesting_person,
                'driver_ID' => $driverId,
                'vehicle_ID' => $vehicleId,
                'num_passengers' => $request->num_passengers,
                'destination' => $request->destination,
                'status' => 'Booked',
                'date' => $request->departure_date,
                'return_date' => $request->departure_date, // Would need return date from request
            ]);

            $request->update(['vehicle_status' => VehicleRequestStatus::COMPLETED->value]);
            $request->user->notify(new RequestApproved($request));
        });
    }

    /**
     * Cancel a request by user
     */
    public function cancelRequest($request): void
    {
        $statusField = $request instanceof VehicleRequest ? 'vehicle_status' : 'vehicle_status';
        $cancelledStatus = $request instanceof VehicleRequest 
            ? VehicleRequestStatus::CANCELLED->value 
            : TravelRequestStatus::CANCELLED->value;

        $request->update([$statusField => $cancelledStatus]);
        $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
        
        // Notify relevant parties
        $request->user->notify(new RequestRejected($request, 'User', 'Cancelled by user'));
    }
}