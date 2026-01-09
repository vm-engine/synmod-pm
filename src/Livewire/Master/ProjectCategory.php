<?php

declare(strict_types=1);

namespace VmEngine\Pm\Livewire\Master;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use VmEngine\Pm\Enums\Documents;
use VmEngine\Pm\Models\ProjectCategory as ModelsProjectCategory;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

class ProjectCategory extends Component
{
    public ?int $editId = null;

    public string $editName = '';

    public string $editCode = '';

    public string $editDescription = '';

    public string $newName = '';

    public string $newCode = '';

    public string $newDescription = '';

    public ?int $indexingId = null;

    public array $documentIndexes = [];

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
            $data = [
                'name' => $this->newName,
                'code' => $this->newCode,
                'description' => $this->newDescription,
            ];

            ModelsProjectCategory::create($data);

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.project_category.create',
                'User '.Auth::user()->name.' created new project category. Name: '.json_encode($data),
                'master.project_category',
                'pm',
            );

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

        $this->dispatch(
            'notify',
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
            $categoryArray = $category->toArray();
            $category->name = $this->editName;
            $category->code = $this->editCode;
            $category->description = $this->editDescription;
            $category->save();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.project_category.update',
                'User '.Auth::user()->name.' updated project category. Old Data: '.json_encode($categoryArray).' New Data: '.json_encode($category->toArray()),
                'master.project_category',
                'pm',
            );

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

        $this->dispatch(
            'notify',
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
            $categoryArray = $category->toArray();
            $category->delete();

            SynAuth::logActivity(
                Auth::id(),
                'pm.master.project_category.delete',
                'User '.Auth::user()->name.' deleted project category. Name: '.json_encode($categoryArray),
                'master.project_category',
                'pm',
            );
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

        $this->dispatch(
            'notify',
            variant: 'success',
            title: 'Success',
            message: __('pm::messages.project_category_deleted_successfully')
        );
    }

    public function loadIndexes(int $id): void
    {
        $this->indexingId = $id;

        try {
            $category = ModelsProjectCategory::findOrFail($id);

            foreach (Documents::cases() as $document) {
                $this->documentIndexes[$document->code()] = [
                    'label' => $document->label().' (Code: '.$document->code().')',
                    'C' => dbconf('pm.documents.'.$category->code.'.'.$document->code().'.next_c_index', 1),
                ];
                if ($document->hasNCIndex()) {
                    $this->documentIndexes[$document->code()]['NC'] = dbconf('pm.documents.'.$category->code.'.'.$document->code().'.next_nc_index', 1);
                }
            }

            $this->dispatch('open-modal-document-index');
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function updateIndexes(): void
    {
        try {
            $category = ModelsProjectCategory::findOrFail($this->indexingId);

            foreach (Documents::cases() as $document) {
                dbconf([
                    'pm.documents.'.$category->code.'.'.$document->code().'.next_c_index' => $this->documentIndexes[$document->code()]['C'],
                ]);
                if ($document->hasNCIndex()) {
                    dbconf([
                        'pm.documents.'.$category->code.'.'.$document->code().'.next_nc_index' => $this->documentIndexes[$document->code()]['NC'],
                    ]);
                }
            }

            $this->dispatch('notify', [
                'variant' => 'success',
                'title' => 'Success',
                'message' => __('pm::config.doc_no_format.messages.save_success_message'),
            ]);

            $this->dispatch('close-modal-document-index');
        } catch (Exception $e) {
            $this->dispatch('notify', [
                'variant' => 'danger',
                'title' => 'Error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    #[On('close-modal-document-index')]
    public function resetIndexes(): void
    {
        $this->indexingId = null;
        $this->documentIndexes = [];
    }

    #[Computed()]
    public function categoryList()
    {
        return ModelsProjectCategory::all();
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
