<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Models\Signature as ModelsSignature;
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
        return 'synapse:query_params:pm.signature-list';
    }

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.signature');
    }

    public function title(): string
    {
        return __('pm::signature.title');
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function delete($token): void
    {
        try {
            $id = ModelsSignature::validateDeleteToken($token);

            $signature = ModelsSignature::query()->findOrFail($id);
            $signatureArray = $signature->toArray();

            if ($signature->file && Storage::disk('public')->exists($signature->file)) {
                Storage::disk('public')->delete($signature->file);
            }

            $signature->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.signature.delete',
                'User '.Auth::user()->name.' deleted a signature: '.json_encode($signatureArray),
                'master.signature',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::signature.not_found'),
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Failed to delete signature: '.$e->getMessage(),
            );

            return;
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::signature.deleted_successfully'),
        );
    }

    #[Computed()]
    public function signatureList()
    {
        $model = ModelsSignature::query();

        if (! empty($this->q) && strlen($this->q) > 2) {
            $model->where(function ($query) {
                $query->where('name', 'like', "%{$this->q}%")
                    ->orWhere('position', 'like', "%{$this->q}%");
            });
        }

        return $model
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
            label: __('pm::menu.be.master.signature'),
            icon: 'fa-solid fa-signature',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
