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
use VmEngine\Pm\Models\DocumentIssuer;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

class Issuer extends Component
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
            $id = DocumentIssuer::validateDeleteToken($token);

            $issuer = DocumentIssuer::query()->findOrFail($id);
            $issuerArray = $issuer->toArray();

            // Delete associated image file
            if ($issuer->header_image && \Storage::disk('public')->exists($issuer->header_image)) {
                \Storage::disk('public')->delete($issuer->header_image);
            }

            $issuer->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.issuer.delete',
                'User '.Auth::user()->name . ' deleted an issuer: '.json_encode($issuerArray),
                'master.issuer',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => __('pm::issuer.not_found'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Failed to delete issuer: '.$e->getMessage(),
            ]);
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Success',
            'message' => __('pm::issuer.deleted_successfully'),
        ]);

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

    public function render()
    {
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.master.parent'),
            icon: 'fa-solid fa-database',
        )->add(
            label: __('pm::menu.be.master.issuer'),
            icon: 'fa-solid fa-building',
        );

        synav()->setActiveMenu('pm.master.issuer');

        return view('pm::livewire.master.issuer', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::issuer.title')));
    }
}
