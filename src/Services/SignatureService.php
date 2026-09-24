<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use VmEngine\Pm\Models\Signature;

class SignatureService
{
    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Create a new signature.
     *
     * @param  array<string, mixed>  $signatureData
     */
    public function create(array $signatureData, ?TemporaryUploadedFile $file = null): Signature
    {
        $data = [
            ...$signatureData,
            'created_by' => Auth::id(),
        ];

        // Process and store signature file
        if ($file) {
            $data['file'] = $this->imageService->processImage(
                $file,
                maxWidth: 500,
                storagePath: 'signatures',
                disk: 'public'
            );
        }

        return Signature::create($data);
    }

    /**
     * Update an existing signature.
     *
     * @param  array<string, mixed>  $signatureData
     */
    public function update(Signature $signature, array $signatureData, ?TemporaryUploadedFile $file = null): Signature
    {
        $data = [
            ...$signatureData,
            'updated_by' => Auth::id(),
        ];

        // Process and store new signature file if uploaded
        if ($file) {
            // Delete old file
            if ($signature->file) {
                $this->imageService->deleteImage($signature->file, 'public');
            }

            $data['file'] = $this->imageService->processImage(
                $file,
                maxWidth: 500,
                storagePath: 'signatures',
                disk: 'public'
            );
        }

        $signature->update($data);

        return $signature->fresh();
    }

    /**
     * Dropdown options for signatures, ordered by name.
     *
     * @return array<int, array{value: int, label: string}>
     */
    public function getOptions(): array
    {
        return Signature::query()
            ->select('id', 'name', 'position')
            ->orderBy('name')
            ->get()
            ->map(fn (Signature $signature) => [
                'value' => (int) $signature->id,
                'label' => $signature->name.' - '.$signature->position,
            ])
            ->all();
    }

    /**
     * Delete a signature.
     */
    public function delete(Signature $signature): bool
    {
        // Delete signature file if exists
        if ($signature->file) {
            $this->imageService->deleteImage($signature->file, 'public');
        }

        return $signature->delete();
    }
}
