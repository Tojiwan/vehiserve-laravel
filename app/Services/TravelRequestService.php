<?php

namespace App\Services;

use App\Models\TravelRequest;
use App\Models\Document;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TravelRequestService
{
    /**
     * Create a new travel request with documents
     */
    public function create(array $data, array $documents = []): TravelRequest
    {
        return DB::transaction(function () use ($data, $documents) {
            $request = TravelRequest::create([
                'user_ID' => auth()->id(),
                'personnel_name' => $data['personnel_name'],
                'official_station' => $data['official_station'],
                'destination' => $data['destination'],
                'purpose' => $data['purpose'],
                'inclusive_date' => $data['inclusive_date'],
                'requesting_for' => $data['requesting_for'] ?? 'N/A',
                'vehicle_request' => $data['vehicle_request'] ?? 'N/A',
            ]);

            // Handle document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    $this->storeDocument($request, $type, $file);
                }
            }

            return $request->load('documents');
        });
    }

    /**
     * Update an existing travel request
     */
    public function update(TravelRequest $request, array $data, array $documents = []): TravelRequest
    {
        return DB::transaction(function () use ($request, $data, $documents) {
            $request->update($data);

            // Handle new document uploads
            foreach ($documents as $type => $file) {
                if ($file instanceof UploadedFile) {
                    // Remove existing document of same type
                    $request->documents()->where('type', $type)->delete();
                    $this->storeDocument($request, $type, $file);
                }
            }

            return $request->load('documents');
        });
    }

    /**
     * Store a document file
     */
    protected function storeDocument(TravelRequest $request, string $type, UploadedFile $file): Document
    {
        $path = $file->store("travel-requests/{$request->id}/{$type}", 'public');
        
        return Document::create([
            'documentable_type' => TravelRequest::class,
            'documentable_id' => $request->id,
            'type' => $type,
            'file_path' => $path,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
        ]);
    }

    /**
     * Cancel a travel request
     */
    public function cancel(TravelRequest $request): void
    {
        $request->update(['vehicle_status' => 'Cancelled by User']);
        $request->approvals()->where('status', 'Waiting')->update(['status' => 'Cancelled']);
    }

    /**
     * Get request statistics for dashboard
     */
    public function getStats(int $userId): array
    {
        return [
            'pending' => TravelRequest::where('user_ID', $userId)
                ->whereIn('vehicle_status', ['Pending Dean', 'Pending VP', 'Pending SUC', 'Pending Motor Pool'])
                ->count(),
            'approved' => TravelRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Completed')
                ->count(),
            'rejected' => TravelRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Rejected')
                ->count(),
            'cancelled' => TravelRequest::where('user_ID', $userId)
                ->where('vehicle_status', 'Cancelled by User')
                ->count(),
        ];
    }
}