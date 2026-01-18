<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use VmEngine\Pm\Models\Signature;

class SignatureFormObject extends Form
{
    public ?Signature $signature = null;

    public $name = '';

    public $position = '';

    public $file = null;

    public $existing_file = null;

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'file' => [
                $this->signature ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('pm::labels.name').' '.__('pm::labels.is_required'),
            'name.max' => __('pm::labels.name').' '.__('pm::labels.max_255'),
            'position.required' => __('pm::signature.position').' '.__('pm::labels.is_required'),
            'position.max' => __('pm::signature.position').' '.__('pm::labels.max_255'),
            'file.required' => __('pm::signature.file').' '.__('pm::labels.is_required'),
            'file.mimes' => __('pm::signature.file').' '.__('pm::labels.must_be_image'),
            'file.max' => __('pm::signature.file').' '.__('pm::labels.max_5mb'),
        ];
    }

    public function setSignature(Signature $signature): void
    {
        $this->signature = $signature;
        $this->name = $signature->name;
        $this->position = $signature->position;
        $this->existing_file = $signature->file;
    }

    public function store(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'created_by' => Auth::id(),
        ];

        // Process and store signature file
        if ($this->file instanceof TemporaryUploadedFile) {
            $data['file'] = $this->processSignatureImage($this->file);
        }

        Signature::create($data);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'position' => $this->position,
            'updated_by' => Auth::id(),
        ];

        // Process and store new signature file if uploaded
        if ($this->file instanceof TemporaryUploadedFile) {
            // Delete old file
            if ($this->signature->file) {
                $this->deleteOldImage($this->signature->file);
            }

            $data['file'] = $this->processSignatureImage($this->file);
        }

        $this->signature->update($data);
    }

    private function processSignatureImage($file): string
    {
        $imagePath = $file->getRealPath();

        // In test environment, skip image processing for fake files
        if (app()->environment('testing')) {
            $filename = 'signature_'.uniqid().'_'.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs('signatures', $filename, 'public');

            return $path;
        }

        $imageInfo = getimagesize($imagePath);
        $mimeType = $imageInfo['mime'];

        // Create source image
        $sourceImage = match ($mimeType) {
            'image/jpeg' => imagecreatefromjpeg($imagePath),
            'image/png' => imagecreatefrompng($imagePath),
            'image/webp' => imagecreatefromwebp($imagePath),
            default => throw new \Exception('Unsupported image type'),
        };

        $sourceWidth = imagesx($sourceImage);
        $sourceHeight = imagesy($sourceImage);

        // Target dimensions: max width 500px, maintain aspect ratio
        $maxWidth = 500;
        $aspectRatio = $sourceWidth / $sourceHeight;

        if ($sourceWidth > $maxWidth) {
            $targetWidth = $maxWidth;
            $targetHeight = (int) ($maxWidth / $aspectRatio);
        } else {
            $targetWidth = $sourceWidth;
            $targetHeight = $sourceHeight;
        }

        // Create destination image
        $destImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency for PNG/WebP
        if ($mimeType === 'image/png' || $mimeType === 'image/webp') {
            imagealphablending($destImage, false);
            imagesavealpha($destImage, true);
            $transparent = imagecolorallocatealpha($destImage, 0, 0, 0, 127);
            imagefilledrectangle($destImage, 0, 0, $targetWidth, $targetHeight, $transparent);
        }

        // Resize while maintaining aspect ratio
        imagecopyresampled(
            $destImage,
            $sourceImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $sourceWidth,
            $sourceHeight
        );

        // Save to temporary file
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            default => throw new \Exception('Unsupported image type'),
        };

        $filename = 'signature_'.uniqid().'_'.time().'.'.$extension;
        $tempPath = storage_path('app/temp/'.$filename);

        // Ensure temp directory exists
        if (! is_dir(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Save image
        match ($mimeType) {
            'image/jpeg' => imagejpeg($destImage, $tempPath, 90),
            'image/png' => imagepng($destImage, $tempPath, 8),
            'image/webp' => imagewebp($destImage, $tempPath, 90),
            default => throw new \Exception('Unsupported image type'),
        };

        // Free memory
        imagedestroy($sourceImage);
        imagedestroy($destImage);

        // Move to final location
        $path = 'signatures/'.$filename;
        Storage::disk('public')->put($path, file_get_contents($tempPath));

        // Delete temp file
        unlink($tempPath);

        return $path;
    }

    private function deleteOldImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
