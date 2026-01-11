<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Master;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Models\BankAccount as ModelsBankAccount;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

class BankAccount extends Component
{
    use WithPagination;

    #[Url()]
    public $q;

    public $limit = 12;

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

            // Delete associated logo file
            if ($account->bank_logo && \Storage::disk('public')->exists($account->bank_logo)) {
                \Storage::disk('public')->delete($account->bank_logo);
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
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => __('pm::bank_account.not_found'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Failed to delete bank account: '.$e->getMessage(),
            ]);
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Success',
            'message' => __('pm::bank_account.deleted_successfully'),
        ]);
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

    public function render()
    {
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.master.parent'),
            icon: 'fa-solid fa-database',
        )->add(
            label: __('pm::menu.be.master.bank_account'),
            icon: 'fa-solid fa-building-columns',
        );

        synav()->setActiveMenu('pm.master.bank_account');

        return view('pm::livewire.master.bank-account', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::bank_account.title')));
    }
}
