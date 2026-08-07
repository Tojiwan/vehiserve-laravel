<?php

namespace App\Services;

use App\Models\TripRequest;
use App\Models\Approval;
use App\Models\User;
use App\Notifications\RequestApproved;
use App\Notifications\RequestRejected;
use App\Notifications\RequestSubmitted;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TripRequestWorkflowService
{
    protected array $approvalOrder = ['Dean', 'Vice President', 'SUC President', 'Motor Pool'];

    /**
     * Submit a new trip request and initialize approval chain
     */
    public function submitTripRequest(TripRequest $trip): void
    {
        DB::transaction(function () use ($trip) {
            // Create approval records for each role in order
            foreach ($this->approvalOrder as $index => $role) {
                Approval::create([
                    'approvable_type' => TripRequest::class,
                    'approvable_id' => $trip->id,
                    'user_ID' => null,
                    'role' => $role,
                    'status' => $index === 0 ? 'Pending' : 'Waiting',
                    'approved_at' => null,
                ]);
            }

            // Update trip status
            $trip->update(['status' => 'Pending Dean']);

            // Notify first approver (Dean)
            $this->notifyApprovers($trip, 'Dean');
        });
    }

    /**
     * Process an approval action
     */
    public function processApproval(Approval $approval, bool $approved, ?string $comment = null): void
    {
        DB::transaction(function () use ($approval, $approved, $comment) {
            $trip = $approval->approvable;
            $role = $approval->role;

            // Update approval record
            $approval->update([
                'user_ID' => auth()->id(),
                'status' => $approved ? 'Approved' : 'Rejected',
                'comment' => $comment,
                'approved_at' => now(),
            ]);

            if (!$approved) {
                $this->rejectTripRequest($trip, $role, $comment);
                return;
            }

            // Find next approval
            $currentIndex = array_search($role, $this->approvalOrder);
            $nextIndex = $currentIndex + 1;

            if ($nextIndex < count($this->approvalOrder)) {
                // Move to next approval stage
                $nextRole = $this->approvalOrder[$nextIndex];
                $nextApproval = $trip->approvals()
                    ->where('role', $nextRole)
                    ->where('status', 'Waiting')
                    ->first();

                if ($nextApproval) {
                    $nextApproval->update(['status' => 'Pending']);
                    $trip->update(['status' => "Pending {$nextRole}"]);
                    $this->notifyApprovers($trip, $nextRole);
                }
            } else {
                // All approvals complete - Motor Pool assigns vehicle/driver
                $this->completeTripRequest($trip);
            }
        });
    }

    /**
     * Reject the trip request
     */
    protected function rejectTripRequest(TripRequest $trip, string $rejectedByRole, ?string $comment): void
    {
        $trip->update(['status' => "Rejected by {$rejectedByRole}"]);

        // Mark remaining approvals as cancelled
        $trip->approvals()
            ->where('status', 'Waiting')
            ->update(['status' => 'Cancelled']);

        // Release vehicle if reserved
        if ($trip->vehicle_ID) {
            \App\Models\Vehicle::where('vehicle_ID', $trip->vehicle_ID)
                ->where('status', 'On Trip')
                ->update(['status' => 'Available']);
        }

        // Notify user
        $trip->user->notify(new RequestRejected($trip, $rejectedByRole, $comment));
    }

    /**
     * Complete the trip request (all approvals done)
     */
    protected function completeTripRequest(TripRequest $trip): void
    {
        $trip->update(['status' => 'Completed']);

        // Notify user
        $trip->user->notify(new RequestApproved($trip));
    }

    /**
     * Assign vehicle and driver (called by Motor Pool staff at final approval)
     */
    public function assignVehicleAndDriver(TripRequest $trip, int $vehicleId, int $driverId, ?string $returnDate = null): void
    {
        DB::transaction(function () use ($trip, $vehicleId, $driverId, $returnDate) {
            $vehicle = \App\Models\Vehicle::findOrFail($vehicleId);
            $driver = \App\Models\Driver::findOrFail($driverId);

            // Check vehicle availability again
            $this->checkVehicleAvailability($vehicleId, $trip->departure_date, $trip->return_date ?? $trip->departure_date, $trip->id);

            // Update vehicle and driver status
            $vehicle->update(['status' => 'On Trip']);
            $driver->update(['status' => 'On Trip']);

            // Update trip request
            $trip->update([
                'vehicle_ID' => $vehicleId,
                'driver_ID' => $driverId,
                'return_date' => $returnDate ?? $trip->return_date,
                'status' => 'Vehicle Assigned',
            ]);

            // Update the Motor Pool approval to mark as assigned
            $mpApproval = $trip->approvals()->where('role', 'Motor Pool')->first();
            if ($mpApproval) {
                $mpApproval->update([
                    'status' => 'Approved',
                    'approved_at' => now(),
                    'user_ID' => auth()->id(),
                ]);
            }

            // Create booking record
            \App\Models\Booking::create([
                'user_ID' => $trip->user_ID,
                'requesting_personnel' => $trip->personnel_name,
                'driver_ID' => $driverId,
                'vehicle_ID' => $vehicleId,
                'num_passengers' => $trip->num_passengers,
                'destination' => $trip->destination,
                'status' => 'Booked',
                'date' => $trip->departure_date,
                'return_date' => $returnDate ?? $trip->return_date,
            ]);
        });
    }

    /**
     * Check vehicle availability for date range
     */
    public function checkVehicleAvailability(int $vehicleId, string $departureDate, string $returnDate, ?int $excludeRequestId = null): void
    {
        $conflicts = \App\Models\TripRequest::where('vehicle_ID', $vehicleId)
            ->where('id', '!=', $excludeRequestId)
            ->whereNotIn('status', [
                'Cancelled by User',
                'Rejected',
                'No Vehicle Available',
                'Completed',
            ])
            ->where(function ($query) use ($departureDate, $returnDate) {
                $query->whereBetween('departure_date', [$departureDate, $returnDate])
                    ->orWhereBetween('return_date', [$departureDate, $returnDate])
                    ->orWhere(function ($q) use ($departureDate, $returnDate) {
                        $q->where('departure_date', '<=', $departureDate)
                            ->where('return_date', '>=', $returnDate);
                    });
            })
            ->exists();

        if ($conflicts) {
            throw new \Exception('Vehicle is already booked for the selected date range.');
        }
    }

    /**
     * Notify approvers for a specific role
     */
    protected function notifyApprovers(TripRequest $trip, string $role): void
    {
        $approvers = User::role($role)->get();

        foreach ($approvers as $approver) {
            if ($approver->can($this->getPermissionForRole($role))) {
                $approver->notify(new RequestSubmitted($trip, $role));
            }
        }
    }

    /**
     * Get permission string for role
     */
    protected function getPermissionForRole(string $role): string
    {
        return match ($role) {
            'Dean' => 'approve travel requests',
            'Vice President' => 'approve travel requests',
            'SUC President' => 'approve travel requests',
            'Motor Pool' => 'approve vehicle requests',
            default => 'view all requests',
        };
    }
}