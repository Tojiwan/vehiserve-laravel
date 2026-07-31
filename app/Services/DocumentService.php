<?php

namespace App\Services;

use App\Models\Document;
use App\Models\VehicleRequest;
use App\Models\TravelRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService
{
    /**
     * Generate PDF for a vehicle request
     */
    public function generateVehicleRequestPdf(VehicleRequest $request): string
    {
        $pdf = \PDF::loadView('pdf.vehicle-request', [
            'request' => $request->load(['user', 'passengers', 'approvals.user', 'documents']),
        ]);

        $filename = "vehicle-request-{$request->id}-" . now()->format('YmdHis') . ".pdf";
        $path = "pdfs/vehicle-requests/{$request->id}/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        // Store as document record
        Document::create([
            'documentable_type' => VehicleRequest::class,
            'documentable_id' => $request->id,
            'type' => 'generated_pdf',
            'file_path' => $path,
            'file_name' => $filename,
            'mime_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
        ]);

        return $path;
    }

    /**
     * Generate PDF for a travel request
     */
    public function generateTravelRequestPdf(TravelRequest $request): string
    {
        $pdf = \PDF::loadView('pdf.travel-request', [
            'request' => $request->load(['user', 'approvals.user', 'documents']),
        ]);

        $filename = "travel-request-{$request->id}-" . now()->format('YmdHis') . ".pdf";
        $path = "pdfs/travel-requests/{$request->id}/{$filename}";
        
        Storage::disk('public')->put($path, $pdf->output());
        
        // Store as document record
        Document::create([
            'documentable_type' => TravelRequest::class,
            'documentable_id' => $request->id,
            'type' => 'generated_pdf',
            'file_path' => $path,
            'file_name' => $filename,
            'mime_type' => 'application/pdf',
            'file_size' => strlen($pdf->output()),
        ]);

        return $path;
    }

    /**
     * Get document URL
     */
    public function getDocumentUrl(Document $document): string
    {
        return Storage::disk('public')->url($document->file_path);
    }

    /**
     * Delete a document
     */
    public function deleteDocument(Document $document): void
    {
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
    }
}