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
use VmEngine\Pm\Models\Signature as ModelsSignature;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

class Signature extends Component
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
            $id = ModelsSignature::validateDeleteToken($token);

            $signature = ModelsSignature::query()->findOrFail($id);
            $signatureArray = $signature->toArray();

            // Delete associated file
            if ($signature->file && \Storage::disk('public')->exists($signature->file)) {
                \Storage::disk('public')->delete($signature->file);
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
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => __('pm::signature.not_found'),
            ]);
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Failed to delete signature: '.$e->getMessage(),
            ]);
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', [
            'variant' => 'success',
            'title' => 'Success',
            'message' => __('pm::signature.deleted_successfully'),
        ]);
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

    public function render()
    {
        $breadcrumbs = Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::menu.be.master.parent'),
            icon: 'fa-solid fa-database',
        )->add(
            label: __('pm::menu.be.master.signature'),
            icon: 'fa-solid fa-signature',
        );

        synav()->setActiveMenu('pm.master.signature');

        return view('pm::livewire.master.signature', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::signature.title')));
    }
}
