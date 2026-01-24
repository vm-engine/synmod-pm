<?php

declare(strict_types=1);

namespace VmEngine\Pm\Services;

use Illuminate\Support\Facades\Auth;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use VmEngine\Pm\Models\BankAccount;

class BankAccountService
{
    public function __construct(
        private ImageProcessingService $imageService
    ) {}

    /**
     * Create a new bank account.
     */
    public function create(array $bankAccountData, ?TemporaryUploadedFile $bankLogo = null): BankAccount
    {
        $data = [
            ...$bankAccountData,
            'created_by' => Auth::id(),
        ];

        // Process and store bank logo
        if ($bankLogo) {
            $data['bank_logo'] = $this->imageService->processImage(
                $bankLogo,
                maxWidth: 500,
                storagePath: 'bank_accounts/logos',
                disk: 'public'
            );
        }

        return BankAccount::create($data);
    }

    /**
     * Update an existing bank account.
     */
    public function update(BankAccount $bankAccount, array $bankAccountData, ?TemporaryUploadedFile $bankLogo = null): BankAccount
    {
        $data = [
            ...$bankAccountData,
            'updated_by' => Auth::id(),
        ];

        // Process and store new bank logo if uploaded
        if ($bankLogo) {
            // Delete old logo
            if ($bankAccount->bank_logo) {
                $this->imageService->deleteImage($bankAccount->bank_logo, 'public');
            }

            $data['bank_logo'] = $this->imageService->processImage(
                $bankLogo,
                maxWidth: 500,
                storagePath: 'bank_accounts/logos',
                disk: 'public'
            );
        }

        $bankAccount->update($data);

        return $bankAccount->fresh();
    }

    /**
     * Delete a bank account.
     */
    public function delete(BankAccount $bankAccount): bool
    {
        // Delete logo if exists
        if ($bankAccount->bank_logo) {
            $this->imageService->deleteImage($bankAccount->bank_logo, 'public');
        }

        return $bankAccount->delete();
    }
}
