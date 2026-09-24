<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use VmEngine\Pm\Models\Signature;
use VmEngine\Pm\Services\SignatureService;

class SignatureFormObject extends Form
{
    public ?Signature $signature = null;

    /**
     * @var string|null
     */
    public $name = '';

    /**
     * @var string|null
     */
    public $position = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $file = null;

    /**
     * @var string|null
     */
    public $existing_file = null;

    /**
     * @return array<string, mixed>
     */
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

    /**
     * @return array<string, string>
     */
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

        $signatureService = app(SignatureService::class);

        $data = [
            'name' => $this->name,
            'position' => $this->position,
        ];

        $signatureService->create($data, $this->file);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $signatureService = app(SignatureService::class);

        $data = [
            'name' => $this->name,
            'position' => $this->position,
        ];

        $this->signature = $signatureService->update($this->signature, $data, $this->file);
    }
}
