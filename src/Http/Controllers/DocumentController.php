<?php

declare(strict_types=1);

namespace VmEngine\Pm\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\ProjectDocument;

class DocumentController extends Controller
{
    /**
     * Download a project document.
     */
    public function download(int $id): BinaryFileResponse|Response
    {
        $document = ProjectDocument::with('project')->findOrFail($id);

        if (! $this->canAccessDocument($document)) {
            abort(403, __('pm::project_document.unauthorized'));
        }

        $filePath = Storage::path($document->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::project_document.file_not_found'));
        }

        $fileName = $document->file_info['original_name']
            ?? $document->file_info['name']
            ?? basename($document->file_path);

        $mimeType = $document->file_info['mime_type']
            ?? $document->file_info['mime']
            ?? 'application/octet-stream';

        return response()->download($filePath, $fileName, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * View/print a project document inline (for PDFs, images, etc).
     */
    public function print(int $id): BinaryFileResponse|Response
    {
        $document = ProjectDocument::with('project')->findOrFail($id);

        if (! $this->canAccessDocument($document)) {
            abort(403, __('pm::project_document.unauthorized'));
        }

        $filePath = Storage::path($document->file_path);

        if (! file_exists($filePath)) {
            abort(404, __('pm::project_document.file_not_found'));
        }

        $mimeType = $document->file_info['mime_type']
            ?? $document->file_info['mime']
            ?? 'application/octet-stream';

        return response()->file($filePath, [
            'Content-Type' => $mimeType,
        ]);
    }

    /**
     * Check if the current user can access the document.
     */
    protected function canAccessDocument(ProjectDocument $document): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        // Dev role has full access
        if ($user->isDev()) {
            return true;
        }

        // Get user's highest role level
        $userRoles = $user->roles()->get();
        $highestLevel = $userRoles->min('level'); // Lower level = higher privilege

        // Admin or higher (level < 10)
        if ($highestLevel < 10) {
            return true;
        }

        // Client role with matching client_id
        if ($user->hasRole('client')) {
            // Check if user's client matches the document's project client
            $client = Client::where('user_id', $user->id)->first();

            return $client && $document->project && $client->id === $document->project->client_id;
        }

        return false;
    }
}
