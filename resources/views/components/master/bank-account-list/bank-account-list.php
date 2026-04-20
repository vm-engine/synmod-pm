<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Models\BankAccount as ModelsBankAccount;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\Synapse\Traits\RemembersQueryParams;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    use RemembersQueryParams;
    use WithPagination;

    #[Url()]
    public $q;

    #[Url()]
    public $limit = 12;

    protected function rememberedParams(): array
    {
        return ['q', 'limit'];
    }

    protected function rememberKey(): string
    {
        return 'synapse:query_params:pm.bank-account-list';
    }

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.bank_account');
    }

    public function title(): string
    {
        return __('pm::bank_account.title');
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function delete($token): void
    {
        try {
            $id = ModelsBankAccount::validateDeleteToken($token);

            $account = ModelsBankAccount::query()->findOrFail($id);
            $accountArray = $account->toArray();

            if ($account->bank_logo && Storage::disk('public')->exists($account->bank_logo)) {
                Storage::disk('public')->delete($account->bank_logo);
            }

            $account->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.bank-account.delete',
                'User '.Auth::user()->name.' deleted a bank account: '.json_encode($accountArray),
                'master.bank-account',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::bank_account.not_found'),
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Failed to delete bank account: '.$e->getMessage(),
            );

            return;
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::bank_account.deleted_successfully'),
        );
    }

    #[Computed()]
    public function accountList()
    {
        $model = ModelsBankAccount::query();

        if (! empty($this->q) && strlen($this->q) > 2) {
            $model->where(function ($query) {
                $query->where('name', 'like', "%{$this->q}%")
                    ->orWhere('number', 'like', "%{$this->q}%")
                    ->orWhere('bank_name', 'like', "%{$this->q}%");
            });
        }

        return $model
            ->orderBy('bank_name', 'asc')
            ->orderBy('name', 'asc')
            ->paginate($this->limit)
            ->onEachSide(1);
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.master.parent'),
            icon: 'fa-solid fa-database',
        )->add(
            label: __('pm::menu.be.master.bank_account'),
            icon: 'fa-solid fa-building-columns',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
