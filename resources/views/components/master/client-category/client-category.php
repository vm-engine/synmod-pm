<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\ClientCategory as ModelsClientCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    public $editId = null;

    public $editName = '';

    public $editDescription = '';

    public $newName = '';

    public $newDescription = '';

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.client_category');
    }

    public function title(): string
    {
        return __('pm::client_category.title');
    }

    public function cancelEdit(): void
    {
        $this->editId = null;
        $this->editName = '';
        $this->editDescription = '';
    }

    public function store(): void
    {
        $this->validate([
            'newName' => 'required|string|max:255',
            'newDescription' => 'nullable|string|max:500',
        ]);

        try {
            ModelsClientCategory::create([
                'name' => $this->newName,
                'description' => $this->newDescription ?: null,
            ]);

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.client_category.create',
                'User '.Auth::user()->name.' created new client category. Name: '.$this->newName,
                'master.client_category',
                'pm',
            );

            $this->newName = '';
            $this->newDescription = '';
        } catch (\Exception $e) {
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
            message: __('pm::messages.client_category_created_successfully'),
        );
    }

    public function edit($id): void
    {
        $this->cancelEdit();
        $category = ModelsClientCategory::findOrFail($id);
        $this->editId = $category->id;
        $this->editName = $category->name;
        $this->editDescription = $category->description;
    }

    public function update($id): void
    {
        $this->validate([
            'editName' => 'required|string|max:255',
            'editDescription' => 'nullable|string|max:500',
        ]);

        try {
            $category = ModelsClientCategory::findOrFail($id);
            $categoryArray = $category->toArray();

            $category->name = $this->editName;
            $category->description = $this->editDescription ?: null;
            $category->save();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.client_category.update',
                'User '.Auth::user()->name.' updated client category. Old Data: '.json_encode($categoryArray).' New Data: '.json_encode($category->toArray()),
                'master.client_category',
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
        } catch (\Exception $e) {
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
            message: __('pm::messages.client_category_updated_successfully'),
        );
    }

    public function delete($token): void
    {
        try {
            $id = ModelsClientCategory::validateDeleteToken($token);
            if (! $id) {
                throw new \Exception(__('pm::messages.invalid_delete_token'));
            }
            $category = ModelsClientCategory::findOrFail($id);
            $categoryArray = $category->toArray();
            $category->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.client_category.delete',
                'User '.Auth::user()->name.' deleted client category. Name: '.json_encode($categoryArray),
                'master.client_category',
                'pm',
            );
        } catch (ModelNotFoundException) {
            $this->dispatch('notify',
                variant: 'danger',
                title: 'Error',
                message: 'Data not found.',
            );

            return;
        } catch (\Exception $e) {
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
            message: __('pm::messages.client_category_deleted_successfully'),
        );
    }

    #[Computed()]
    public function categoryList()
    {
        return ModelsClientCategory::orderBy('name')->get();
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
            label: __('pm::menu.be.master.client_category'),
            icon: 'fa-solid fa-tags',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
