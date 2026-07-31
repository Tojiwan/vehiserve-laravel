<?php

namespace App\Services;

use App\Models\VehicleRequest;
use App\Models\Passenger;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VehicleRequestService
{
    /**
     * Create a new vehicle request with passengers and documents
     */
    public function create(array $data, array $passengers = [], array $documents = []): VehicleRequest
    {
        return DB::transaction(function () use ($data, $passengers, $documents) {
            $request = VehicleRequest::create([
                'user_ID' => auth()->id(),
                'request_date' => $data['request_date'],
                'requesting_person' => $data['requesting_person'],
                'office_college' => $data['office_college'],
                'destination' => $data['destination'],
                'purpose' => $data['purpose'],
                'departure_date' => $data['departure_date'],
                'departure_time' => $data['departure_time'],
                'num_passengers' => $data['num_passengers'] ?? 1,
            ]);

            // Create passengers
            foreach ($passengers as $passengerName) {
                if (!empty(trim($passengerName))) {
                    Passenger::create([
                        'request_id' => $request->id,
                        'passenger_name' => trim($passengerName),
                    ]);
                }
            }

            // Handle document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeDocument($request, $type, $file);
                }
            }

            return $request->load(['passengers', 'documents']);
        });
    }

    /**
     * Update an existing vehicle request
     */
    public function update(VehicleRequest $request, array $data, array $passengers = [], array $documents = []): VehicleRequest
    {
        return DB::transaction(function () use ($request, $data, $passengers, $documents) {
            $request->update($data);

            // Sync passengers
            if (!empty($passengers)) {
                $request->passengers()->delete();
                foreach ($passengers as $passengerName) {
                    if (!empty(trim($passengerName))) {
                        Passenger::create([
                            'request_id' => $request->id,
                            'passenger_name' => trim($passengerName),
                        ]);
                    }
                }
            }

            // Handle new document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    // Remove existing document of same type
                    $request->documents()->where('type', $type)->delete();
                    $this->storeDocument($request, $type, $file);
                }
            }

            return $request->load(['passengers', 'documents']);
        });
    }

    /**
     * Store a document file
     */
    protected function storeDocument(VehicleRequest $request, string $type, UploadedFile $file): Document
    {
        $path = $file->store("vehicle-requests/{$request->id}/{$type}", 'public');
        
        return Document::create([
            'documentable_type' => VehicleRequest::class,
            'documentable_id' => $request->id,
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Cancel a vehicle request
     */
    public function cancel(VehicleRequest $request): void
    {
        $this->authorize('delete', $request);
        
        $request->update(['vehicle_status' => 'Cancelled by User']);
        $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
    }

    /**
     * Get request statistics for dashboard
     */
    public function getStats(int $userId): array
    {
        return [
            'pending' => VehicleRequest::where('user_ID', $userId)
                ->whereIn('vehicle_status', ['Pending Motor Pool', 'Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Final MP Approval'])
                ->count(),
            'approved' => VehicleRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Completed')
                ->count(),
            'rejected' => VehicleRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Rejected')
                ->count(),
            'cancelled' => VehicleRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Cancelled by User')
                ->count(),
        ];
    }
}