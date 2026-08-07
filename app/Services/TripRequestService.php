<?php

namespace App\Services;

use App\Models\TripRequest;
use App\Models\Passenger;
use App\Models\Document;
use App\Models\Vehicle;
use App\Models\Driver;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TripRequestService
{
    /**
     * Create a new trip request with passengers and documents
     */
    public function create(array $data, array $passengers = [], array $documents = []): TripRequest
    {
        return DB::transaction(function () use ($data, $passengers, $documents) {
            // Check vehicle availability if vehicle is selected
            if (!empty($data['vehicle_ID'])) {
                $this->checkVehicleAvailability(
                    $data['vehicle_ID'],
                    $data['departure_date'],
                    $data['return_date'],
                    $data['id'] ?? null
                );
            }

            // Create the trip request
            $trip = TripRequest::create([
                'user_ID' => auth()->id(),
                'personnel_name' => $data['personnel_name'],
                'official_station' => $data['official_station'],
                'destination' => $data['destination'],
                'purpose' => $data['purpose'],
                'inclusive_date' => $data['inclusive_date'],
                'requesting_for' => $data['requesting_for'],
                'departure_date' => $data['departure_date'],
                'departure_time' => $data['departure_time'],
                'return_date' => $data['return_date'] ?? null,
                'num_passengers' => $data['num_passengers'] ?? 1,
                'vehicle_ID' => $data['vehicle_ID'] ?? null,
                'driver_ID' => $data['driver_ID'] ?? null,
                'status' => 'Pending Dean',
            ]);

            // Create passengers
            foreach ($passengers as $passengerName) {
                if (!empty(trim($passengerName))) {
                    Passenger::create([
                        'request_id' => $trip->id,
                        'passenger_name' => trim($passengerName),
                    ]);
                }
            }

            // Handle document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeDocument($trip, $type, $file);
                }
            }

            // Initialize approval workflow
            $this->initializeApprovalWorkflow($trip);

            return $trip->load(['passengers', 'documents', 'approvals.user', 'vehicle', 'driver']);
        });
    }

    /**
     * Check vehicle availability for the given date range
     */
    public function checkVehicleAvailability(int $vehicleId, string $departureDate, ?string $returnDate, ?int $excludeRequestId = null): void
    {
        $returnDate = $returnDate ?? $departureDate;

        $conflicts = TripRequest::where('vehicle_ID', $vehicleId)
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
            throw new \Exception('Vehicle is already booked for the selected date range. Please choose another vehicle or date range.');
        }
    }

    /**
     * Store a document file
     */
    protected function storeDocument(TripRequest $trip, string $type, UploadedFile $file): Document
    {
        $path = $file->store("trip-requests/{$trip->id}/{$type}", 'public');

        return Document::create([
            'documentable_type' => TripRequest::class,
            'documentable_id' => $trip->id,
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Initialize the approval workflow
     */
    protected function initializeApprovalWorkflow(TripRequest $trip): void
    {
        $roles = ['Dean', 'Vice President', 'SUC President', 'Motor Pool'];

        foreach ($roles as $index => $role) {
            \App\Models\Approval::create([
                'approvable_type' => TripRequest::class,
                'approvable_id' => $trip->id,
                'user_ID' => null,
                'role' => $role,
                'status' => $index === 0 ? 'Pending' : 'Waiting',
                'approved_at' => null,
            ]);
        }
    }

    /**
     * Update an existing trip request
     */
    public function update(TripRequest $trip, array $data, array $passengers = [], array $documents = []): TripRequest
    {
        return DB::transaction(function () use ($trip, $data, $passengers, $documents) {
            // Check vehicle availability if vehicle changed
            if (!empty($data['vehicle_ID']) && $data['vehicle_ID'] != $trip->vehicle_ID) {
                $this->checkVehicleAvailability(
                    $data['vehicle_ID'],
                    $data['departure_date'] ?? $trip->departure_date,
                    $data['return_date'] ?? $trip->return_date,
                    $trip->id
                );
            }

            // Update trip request
            $trip->update([
                'personnel_name' => $data['personnel_name'],
                'official_station' => $data['official_station'],
                'destination' => $data['destination'],
                'purpose' => $data['purpose'],
                'inclusive_date' => $data['inclusive_date'],
                'requesting_for' => $data['requesting_for'],
                'departure_date' => $data['departure_date'],
                'departure_time' => $data['departure_time'],
                'return_date' => $data['return_date'] ?? null,
                'num_passengers' => $data['num_passengers'] ?? 1,
                'vehicle_ID' => $data['vehicle_ID'] ?? null,
                'driver_ID' => $data['driver_ID'] ?? null,
            ]);

            // Sync passengers
            if (!empty($passengers)) {
                $trip->passengers()->delete();
                foreach ($passengers as $passengerName) {
                    if (!empty(trim($passengerName))) {
                        Passenger::create([
                            'request_id' => $trip->id,
                            'passenger_name' => trim($passengerName),
                        ]);
                    }
                }
            }

            // Handle new document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    // Remove existing document of same type
                    $trip->documents()->where('type', $type)->delete();
                    $this->storeDocument($trip, $type, $file);
                }
            }

            return $trip->load(['passengers', 'documents', 'approvals.user', 'vehicle', 'driver']);
        });
    }

    /**
     * Cancel a trip request
     */
    public function cancel(TripRequest $trip): void
    {
        if (in_array($trip->status, ['Completed', 'Cancelled by User'])) {
            throw new \Exception('Cannot cancel this request.');
        }

        $trip->update(['status' => 'Cancelled by User']);
        $trip->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);

        // Release vehicle if reserved
        if ($trip->vehicle_ID) {
            Vehicle::where('vehicle_ID', $trip->vehicle_ID)
                ->where('status', 'On Trip')
                ->update(['status' => 'Available']);
        }
    }
}