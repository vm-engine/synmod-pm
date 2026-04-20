<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Division as ModelsDivision;
use VmEngine\Pm\Models\SubDivision;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    public $editId = null;

    public $editText = '';

    public $editCode = '';

    public $editSubId = null;

    public $editSubText = '';

    public $newDivision = '';

    public $newDivisionCode = '';

    public $newSubDivision = [];

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.division');
    }

    public function title(): string
    {
        return __('pm::division.division');
    }

    public function cancelEdit(): void
    {
        $this->editId = null;
        $this->editText = '';
        $this->editCode = '';

        $this->editSubId = null;
        $this->editSubText = '';
    }

    public function store(): void
    {
        try {
            $this->validate([
                'newDivision' => 'required',
                'newDivisionCode' => 'required',
            ]);

            $data = [
                'name' => $this->newDivision,
                'code' => $this->newDivisionCode,
            ];

            ModelsDivision::create($data);

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.create',
                'User '.Auth::user()->name.' created new division. Name: '.json_encode($data),
                'master.client_category',
                'pm',
            );

            $this->newDivision = '';
            $this->newDivisionCode = '';
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_created_successfully'),
        );
    }

    public function edit($id): void
    {
        $this->cancelEdit();
        $division = ModelsDivision::findOrFail($id);
        $this->editId = $division->id;
        $this->editText = $division->name;
        $this->editCode = $division->code;
    }

    public function update($id): void
    {
        try {
            $this->validate([
                'editText' => 'required',
                'editCode' => 'required',
            ]);

            $division = ModelsDivision::findOrFail($id);
            $divisionArray = $division->toArray();
            $division->name = $this->editText;
            $division->code = $this->editCode;
            $division->save();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.update',
                'User '.Auth::user()->name.' updated division. Old Data: '.json_encode($divisionArray).' New Data: '.json_encode($division->toArray()),
                'master.division',
                'pm',
            );

            $this->cancelEdit();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Data not found.',
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_updated_successfully'),
        );
    }

    public function delete($token): void
    {
        try {
            $id = ModelsDivision::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }
            $division = ModelsDivision::findOrFail($id);
            $divisionArray = $division->toArray();
            $subDivisionArray = $division->subDivisions()->get()->toArray();
            $division->subDivisions()->delete();
            $division->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.update',
                'User '.Auth::user()->name.' deleted division and all it\'s sub divisions. Data: '.json_encode($divisionArray).' Sub Divisions: '.json_encode($subDivisionArray),
                'master.division',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Data not found.',
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.division_deleted_successfully'),
        );
    }

    public function storeSub($divisionId): void
    {
        try {
            $division = ModelsDivision::findOrFail($divisionId);
            $division->subDivisions()->create([
                'name' => $this->newSubDivision[$divisionId] ?? '',
            ]);

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.sub.create',
                'User '.Auth::user()->name.' created new sub division under '.$division->name.' division. Name: '.($this->newSubDivision[$divisionId] ?? ''),
                'master.division.sub',
                'pm',
            );

            $this->newSubDivision[$divisionId] = '';
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_created_successfully'),
        );
    }

    public function editSub($id): void
    {
        $this->cancelEdit();
        $subDivision = SubDivision::findOrFail($id);
        $this->editSubId = $subDivision->id;
        $this->editSubText = $subDivision->name;
    }

    public function updateSub($id): void
    {
        try {
            $subDivision = SubDivision::findOrFail($id);
            $subDivisionArray = $subDivision->toArray();
            $subDivision->name = $this->editSubText;
            $subDivision->save();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.sub.update',
                'User '.Auth::user()->name.' updated sub division. Old Data: '.json_encode($subDivisionArray).' New Data: '.json_encode($subDivision->toArray()),
                'master.division.sub',
                'pm',
            );

            $this->cancelEdit();
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Data not found.',
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_updated_successfully'),
        );
    }

    public function deleteSub($token): void
    {
        try {
            $id = SubDivision::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }
            $subDivision = SubDivision::findOrFail($id);
            $subDivisionArray = $subDivision->toArray();
            $subDivision->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.division.sub.delete',
                'User '.Auth::user()->name.' deleted sub division. Data: '.json_encode($subDivisionArray),
                'master.division.sub',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Data not found.',
            );

            return;
        } catch (Exception $e) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: $e->getMessage(),
            );

            return;
        }

        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.sub_division_deleted_successfully'),
        );
    }

    #[Computed()]
    public function divisionList()
    {
        return ModelsDivision::with('subDivisions')->get();
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
            label: __('pm::menu.be.master.division'),
            icon: 'fa-solid fa-building',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
