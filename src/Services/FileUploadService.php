<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class FileUploadService
{
    /**
     * Upload NPWP image and delete old image if exists.
     */
    public function uploadNpwpImage(?TemporaryUploadedFile $file, ?string $oldPath = null): ?string
    {
        if (! $file) {
            return $oldPath;
        }

        // Store new file
        $newPath = $file->store('clients/npwp', 'local');

        // Delete old file if exists
        if ($oldPath && Storage::disk('local')->exists($oldPath)) {
            Storage::disk('local')->delete($oldPath);
        }

        return $newPath;
    }

    /**
     * Delete NPWP image from storage.
     */
    public function deleteNpwpImage(string $path): bool
    {
        if (Storage::disk('local')->exists($path)) {
            return Storage::disk('local')->delete($path);
        }

        return false;
    }
}
