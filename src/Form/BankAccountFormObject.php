<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\Form;
use VmEngine\Pm\Models\BankAccount;
use VmEngine\Pm\Services\BankAccountService;

class BankAccountFormObject extends Form
{
    public ?BankAccount $bankAccount = null;

    /**
     * @var string|null
     */
    public $name = '';

    /**
     * @var string|null
     */
    public $number = '';

    /**
     * @var string|null
     */
    public $bank_name = '';

    /**
     * @var string|null
     */
    public $swift_code = '';

    /**
     * @var TemporaryUploadedFile|null
     */
    public $bank_logo = null;

    /**
     * @var string|null
     */
    public $existing_bank_logo = null;

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'bank_name' => 'required|string|max:255',
            'swift_code' => 'nullable|string|max:50',
            'bank_logo' => [
                $this->bankAccount ? 'nullable' : 'required',
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
            'number.required' => __('pm::bank_account.number').' '.__('pm::labels.is_required'),
            'number.max' => __('pm::bank_account.number').' '.__('pm::labels.max_255'),
            'bank_name.required' => __('pm::bank_account.bank_name').' '.__('pm::labels.is_required'),
            'bank_name.max' => __('pm::bank_account.bank_name').' '.__('pm::labels.max_255'),
            'bank_logo.required' => __('pm::bank_account.bank_logo').' '.__('pm::labels.is_required'),
            'bank_logo.mimes' => __('pm::bank_account.bank_logo').' '.__('pm::labels.must_be_image'),
            'bank_logo.max' => __('pm::bank_account.bank_logo').' '.__('pm::labels.max_5mb'),
        ];
    }

    public function setAccount(BankAccount $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
        $this->name = $bankAccount->name;
        $this->number = $bankAccount->number;
        $this->bank_name = $bankAccount->bank_name;
        $this->swift_code = $bankAccount->swift_code;
        $this->existing_bank_logo = $bankAccount->bank_logo;
    }

    public function store(): void
    {
        $this->validate();

        $bankAccountService = app(BankAccountService::class);

        $data = [
            'name' => $this->name,
            'number' => $this->number,
            'bank_name' => $this->bank_name,
            'swift_code' => $this->swift_code ?: null,
        ];

        $bankAccountService->create($data, $this->bank_logo);

        $this->reset();
    }

    public function update(): void
    {
        $this->validate();

        $bankAccountService = app(BankAccountService::class);

        $data = [
            'name' => $this->name,
            'number' => $this->number,
            'bank_name' => $this->bank_name,
            'swift_code' => $this->swift_code ?: null,
        ];

        $this->bankAccount = $bankAccountService->update($this->bankAccount, $data, $this->bank_logo);
    }
}
