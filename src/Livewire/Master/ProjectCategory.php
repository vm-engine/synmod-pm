<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Master;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\ProjectCategory as ModelsProjectCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ProjectCategory extends Component
{
    public $editId = null;

    public $editName = '';

    public $editCode = '';

    public $editDescription = '';

    public $newName = '';

    public $newCode = '';

    public $newDescription = '';

    public function cancelEdit()
    {
        $this->editId = null;
        $this->editName = '';
        $this->editCode = '';
        $this->editDescription = '';
    }

    public function store()
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newCode' => 'required|string|max:50|unique:project_categories,code',
            'newDescription' => 'nullable|string|max:500',
        ]);

        try {
            ModelsProjectCategory::create([
                'name' => $this->newName,
                'code' => $this->newCode,
                'description' => $this->newDescription,
            ]);

            $this->newName = '';
            $this->newCode = '';
            $this->newDescription = '';
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);

            return;
        }

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.project_category_created_successfully')
        );
    }

    public function edit($id)
    {
        $this->cancelEdit();
        $category = ModelsProjectCategory::findOrFail($id);
        $this->editId = $category->id;
        $this->editName = $category->name;
        $this->editCode = $category->code;
        $this->editDescription = $category->description;
    }

    public function update($id)
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editCode' => 'required|string|max:50|unique:project_categories,code,'.$id,
            'editDescription' => 'nullable|string|max:500',
        ]);

        try {
            $category = ModelsProjectCategory::findOrFail($id);
            $category->name = $this->editName;
            $category->code = $this->editCode;
            $category->description = $this->editDescription;
            $category->save();

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

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.project_category_updated_successfully')
        );
    }

    public function delete($token)
    {
        try {
            $id = ModelsProjectCategory::validateDeleteToken($token);
            if (! $id) {
                throw new Exception(__('pm::messages.invalid_delete_token'));
            }
            $category = ModelsProjectCategory::findOrFail($id);
            $category->delete();
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

        $this->dispatch('notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.project_category_deleted_successfully')
        );
    }

    #[Computed()]
    public function categoryList()
    {
        return ModelsProjectCategory::orderBy('name')->get();
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
            label: __('pm::menu.be.master.project_category'),
            icon: 'fa-solid fa-folder-tree',
        );

        synav()->setActiveMenu('pm.master.project_category');

        return view('pm::livewire.master.project-category', [
            'breadcrumbs' => $breadcrumbs,
        ])->title(page_title(__('pm::project_category.list_title')));
    }
}
