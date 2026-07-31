<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentDownloadController extends Controller
{
    public function download(Request $request, string $path)
    {
        $user = Auth::user();
        
        // Verify the file exists
        if (!Storage::disk('public')->exists($path)) {
            abort(404, 'File not found');
        }

        // Verify ownership - check if the path belongs to user's documents
        // This is a basic check; you might want more sophisticated ownership verification
        $filePath = "vehicle-requests/{$user->id}/";
        $travelPath = "travel-requests/{$user->id}/";
        $pdfPath = "pdfs/vehicle-requests/{$user->id}/";
        $travelPdfPath = "pdfs/travel-requests/{$user->id}/";

        $authorized = str_starts_with($path, $filePath) 
            || str_starts_with($path, $travelPath)
            || str_starts_with($path, $pdfPath)
            || str_starts_with($path, $travelPdfPath);

        if (!$authorized && !$user->hasAnyRole(['Admin', 'Staff', 'Super Admin'])) {
            abort(403, 'Unauthorized access to this document');
        }

        $fullPath = Storage::disk('public')->path($path);
        $filename = basename($path);

        return response()->download($fullPath, $filename);
    }
}