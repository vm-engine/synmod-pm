<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Pm\Services\IssuerService;

class IssuerFormObject extends Form
{
    public ?DocumentIssuer $issuer = null;

    public string $name = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $header_image = null;

    /**
     * @var TemporaryUploadedFile|null
     */
    public $footer_image = null;

    public string $tax_id_name = '';

    public string $tax_id_address = '';

    public string $tax_id_number = '';

    public ?string $existing_header_image = null;

    public ?string $existing_footer_image = null;

    public function setIssuer(DocumentIssuer $issuer): void
    {
        $this->issuer = $issuer;
        $this->name = $issuer->name;
        $this->tax_id_name = $issuer->tax_id_name;
        $this->tax_id_address = $issuer->tax_id_address;
        $this->tax_id_number = $issuer->tax_id_number;
        $this->existing_header_image = $issuer->header_image;
        $this->existing_footer_image = $issuer->footer_image;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'header_image' => [
                $this->issuer ? 'nullable' : 'required',
                'file',
                'mimes:jpeg,png,webp',
                'max:5120',
            ],
            'footer_image' => ['nullable', 'file', 'mimes:jpeg,png,webp', 'max:5120'],
            'tax_id_name' => 'required|string|max:255',
            'tax_id_address' => 'required|string',
            'tax_id_number' => ['required', 'numeric', 'digits:16'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => __('pm::issuer.validation.name_required'),
            'header_image.required' => __('pm::issuer.validation.header_image_required'),
            'header_image.file' => __('pm::issuer.validation.header_image_file'),
            'header_image.mimes' => __('pm::issuer.validation.header_image_mimes'),
            'header_image.max' => __('pm::issuer.validation.header_image_max'),
            'footer_image.file' => __('pm::issuer.validation.footer_image_file'),
            'footer_image.mimes' => __('pm::issuer.validation.footer_image_mimes'),
            'footer_image.max' => __('pm::issuer.validation.footer_image_max'),
            'tax_id_name.required' => __('pm::issuer.validation.tax_id_name_required'),
            'tax_id_address.required' => __('pm::issuer.validation.tax_id_address_required'),
            'tax_id_number.required' => __('pm::issuer.validation.tax_id_number_required'),
            'tax_id_number.numeric' => __('pm::issuer.validation.tax_id_number_numeric'),
            'tax_id_number.digits' => __('pm::issuer.validation.tax_id_number_digits'),
        ];
    }

    public function store(): void
    {
        $this->validate();

        $issuerService = app(IssuerService::class);

        $data = [
            'name' => $this->name,
            'tax_id_name' => $this->tax_id_name,
            'tax_id_address' => $this->tax_id_address,
            'tax_id_number' => $this->tax_id_number,
        ];

        $issuerService->create($data, $this->header_image, $this->footer_image);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $issuerService = app(IssuerService::class);

        $data = [
            'name' => $this->name,
            'tax_id_name' => $this->tax_id_name,
            'tax_id_address' => $this->tax_id_address,
            'tax_id_number' => $this->tax_id_number,
        ];

        $this->issuer = $issuerService->update($this->issuer, $data, $this->header_image, $this->footer_image);
    }
}
