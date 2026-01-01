<?php

namespace VmEngine\Pm\Livewire\Master;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Division as ModelsDivision;
use VmEngine\Pm\Models\SubDivision;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class Division extends Component
{
    public $editId = null;

    public $editText = '';

    public $editCode = '';

    public $editSubId = null;

    public $editSubText = '';

    public $newDivision = '';

    public $newDivisionCode = '';

    public $newSubDivision = [];

    public function cancelEdit()
    {
        $this->editId = null;
        $this->editText = '';

        $this->editSubId = null;
        $this->editSubText = '';
    }

    public function store()
    {
        try {
            $this->validate([
                'newDivision' => 'required',
                'newDivisionCode' => 'required',
            ]);

            ModelsDivision::create([
                'name' => $this->newDivision,
                'code' => $this->newDivisionCode,
            ]);

            $this->newDivision = '';
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_created_successfully')
        );
    }

    public function edit($id)
    {
        $this->cancelEdit();
        $division = ModelsDivision::findOrFail($id);
        $this->editId = $division->id;
        $this->editText = $division->name;
        $this->editCode = $division->code;
    }

    public function update($id)
    {
        try {
            $this->validate([
                'editText' => 'required',
                'editCode' => 'required',
            ]);

            $division = ModelsDivision::findOrFail($id);
            $division->name = $this->editText;
            $division->code = $this->editCode;
            $division->save();

            $this->cancelEdit();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Data not found.',
            ]);

            return;
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_updated_successfully')
        );
    }

    public function delete($token)
    {
        try {
            $id = ModelsDivision::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }
            $division = ModelsDivision::findOrFail($id);
            $division->subDivisions()->delete();
            $division->delete();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Data not found.',
            ]);

            return;
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_deleted_successfully')
        );
    }

    public function storeSub($divisionId)
    {
        try {
            $division = ModelsDivision::findOrFail($divisionId);
            $division->subDivisions()->create([
                'name' => $this->newSubDivision[$divisionId] ?? '',
            ]);

            $this->newSubDivision[$divisionId] = '';
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_created_successfully')
        );
    }

    public function editSub($id)
    {
        $this->cancelEdit();
        $subDivision = SubDivision::findOrFail($id);
        $this->editSubId = $subDivision->id;
        $this->editSubText = $subDivision->name;
    }

    public function updateSub($id)
    {
        try {
            $subDivision = SubDivision::findOrFail($id);
            $subDivision->name = $this->editSubText;
            $subDivision->save();

            $this->cancelEdit();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Data not found.',
            ]);

            return;
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_updated_successfully')
        );
    }

    public function deleteSub($token)
    {
        try {
            $id = SubDivision::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }
            $subDivision = SubDivision::findOrFail($id);
            $subDivision->delete();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => 'Data not found.',
            ]);

            return;
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        // Dispatch browser event for immediate notification (no page reload)
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_deleted_successfully')
        );
    }

    #[Computed()]
    public function divisionList()
    {
        $model = ModelsDivision::with('subDivisions');

        return $model->get();
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
            label: __('pm::menu.be.master.division'),
            icon: 'fa-solid fa-building',
        );

        synav()->setActiveMenu('pm.master.division');

        return view('pm::livewire.master.division', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::division.division')));
    }
}
