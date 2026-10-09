<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Services\Concerns\RecordsActivity;

class IssuerService
{
    use RecordsActivity;

    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Create a new document issuer.
     *
     * @param  array<string, mixed>  $issuerData
     */
    public function create(array $issuerData, ?UploadedFile $headerImage = null, ?UploadedFile $footerImage = null): DocumentIssuer
    {
        $data = [...$issuerData];

        // Process and store header image
        if ($headerImage) {
            $data['header_image'] = $this->imageService->processImage(
                $headerImage,
                maxWidth: 1200,
                storagePath: 'issuers/headers',
                disk: 'public'
            );
        }

        if ($footerImage) {
            $data['footer_image'] = $this->imageService->processImage(
                $footerImage,
                maxWidth: 2400,
                storagePath: 'issuers/footers',
                disk: 'public'
            );
        }

        $issuer = DocumentIssuer::create($data);

        // Log activity
        $this->logActivity('create', $issuer, $data);

        return $issuer;
    }

    /**
     * Update an existing document issuer.
     *
     * @param  array<string, mixed>  $issuerData
     */
    public function update(DocumentIssuer $issuer, array $issuerData, ?UploadedFile $headerImage = null, ?UploadedFile $footerImage = null): DocumentIssuer
    {
        $oldData = $issuer->toArray();
        $data = [...$issuerData];

        // Process and store new header image if uploaded
        if ($headerImage) {
            // Delete old image
            if ($issuer->header_image) {
                $this->imageService->deleteImage($issuer->header_image, 'public');
            }

            $data['header_image'] = $this->imageService->processImage(
                $headerImage,
                maxWidth: 1200,
                storagePath: 'issuers/headers',
                disk: 'public'
            );
        }

        if ($footerImage) {
            if ($issuer->footer_image) {
                $this->imageService->deleteImage($issuer->footer_image, 'public');
            }

            $data['footer_image'] = $this->imageService->processImage(
                $footerImage,
                maxWidth: 2400,
                storagePath: 'issuers/footers',
                disk: 'public'
            );
        }

        $issuer->update($data);

        // Log activity
        $this->logActivity('update', $issuer, $data, $oldData);

        return $issuer->fresh();
    }

    /**
     * Delete a document issuer.
     */
    public function delete(DocumentIssuer $issuer): bool
    {
        $issuerData = $issuer->toArray();

        // Delete header image if exists
        if ($issuer->header_image) {
            $this->imageService->deleteImage($issuer->header_image, 'public');
        }

        if ($issuer->footer_image) {
            $this->imageService->deleteImage($issuer->footer_image, 'public');
        }

        $deleted = $issuer->delete();

        // Log activity
        $this->logActivity('delete', $issuer, $issuerData);

        return $deleted;
    }

    /**
     * Dropdown options for document issuers, ordered by name.
     *
     * @return array<int, array{value: int, label: string}>
     */
    public function getOptions(): array
    {
        return DocumentIssuer::query()
            ->select('id', 'name', 'tax_id_number')
            ->orderBy('name')
            ->get()
            ->map(fn (DocumentIssuer $issuer) => [
                'value' => (int) $issuer->id,
                'label' => $issuer->name.' ('.$issuer->tax_id_number.')',
            ])
            ->all();
    }

    /**
     * Log issuer activity.
     *
     * @param  array<string, mixed>|null  $newData
     * @param  array<string, mixed>|null  $oldData
     */
    private function logActivity(string $action, DocumentIssuer $issuer, ?array $newData = null, ?array $oldData = null): void
    {
        $message = match ($action) {
            'create' => 'User '.Auth::user()->name.' created a new issuer: '.json_encode($newData),
            'update' => 'User '.Auth::user()->name.' updated an issuer. Old Data: '.json_encode($oldData).' New Data: '.json_encode($newData),
            'delete' => 'User '.Auth::user()->name.' deleted an issuer. Data: '.json_encode($newData),
            default => 'User '.Auth::user()->name.' performed '.$action.' on issuer.',
        };

        $this->recordActivity(
            'pm.master.issuer.'.$action,
            $message,
            'master.issuer',
        );
    }
}
