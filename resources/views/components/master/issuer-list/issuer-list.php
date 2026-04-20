<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\Pm\Models\DocumentIssuer;
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
        return 'synapse:query_params:pm.issuer-list';
    }

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.issuer');
    }

    public function title(): string
    {
        return __('pm::issuer.title');
    }

    public function updated(): void
    {
        $this->resetPage();
    }

    public function delete($token): void
    {
        try {
            $id = DocumentIssuer::validateDeleteToken($token);

            $issuer = DocumentIssuer::query()->findOrFail($id);
            $issuerArray = $issuer->toArray();

            if ($issuer->header_image && Storage::disk('public')->exists($issuer->header_image)) {
                Storage::disk('public')->delete($issuer->header_image);
            }

            $issuer->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.issuer.delete',
                'User '.Auth::user()->name.' deleted an issuer: '.json_encode($issuerArray),
                'master.issuer',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: __('pm::issuer.not_found'),
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Failed to delete issuer: '.$e->getMessage(),
            );

            return;
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::issuer.deleted_successfully'),
        );
    }

    #[Computed()]
    public function issuerList()
    {
        $model = DocumentIssuer::query();

        if (! empty($this->q) && strlen($this->q) > 2) {
            $model->where(function ($query) {
                $query->where('name', 'like', "%{$this->q}%")
                    ->orWhere('tax_id_number', 'like', "%{$this->q}%")
                    ->orWhere('tax_id_name', 'like', "%{$this->q}%");
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
            label: __('pm::menu.be.master.issuer'),
            icon: 'fa-solid fa-building',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
