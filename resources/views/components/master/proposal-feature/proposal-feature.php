<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\ProposalFeatureItem;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    public string $newName = '';

    public string $newDescription = '';

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.proposal_feature');
    }

    public function title(): string
    {
        return __('pm::proposal.feature_items_title');
    }

    /**
     * @return Collection<int, ProposalFeatureItem>
     */
    #[Computed]
    public function items(): Collection
    {
        return ProposalFeatureItem::query()->orderBy('sort')->orderBy('id')->get();
    }

    public function store(): void
    {
        $this->validate(['newName' => 'required|string|max:255', 'newDescription' => 'nullable|string|max:2000']);
        $item = ProposalFeatureItem::create([
            'name' => trim($this->newName),
            'description' => trim($this->newDescription),
            'default_checked' => true,
            'active' => true,
            'sort' => ((int) ProposalFeatureItem::query()->max('sort')) + 10,
        ]);
        SynAuth::logActivity(Auth::id(), 'pm.master.proposal_feature.create', 'Created proposal feature '.$item->name, 'master.proposal_feature', 'pm');
        $this->reset('newName', 'newDescription');
        unset($this->items);
    }

    public function updateItem(int $id, string $field, string $value): void
    {
        abort_unless(in_array($field, ['name', 'description'], true), 400);
        $value = trim($value);
        if ($field === 'name' && $value === '') {
            return;
        }
        ProposalFeatureItem::query()->findOrFail($id)->update([$field => $value]);
        unset($this->items);
    }

    public function toggle(int $id, string $field): void
    {
        abort_unless(in_array($field, ['default_checked', 'active'], true), 400);
        $item = ProposalFeatureItem::query()->findOrFail($id);
        $item->update([$field => ! $item->{$field}]);
        unset($this->items);
    }

    public function updateSort(int $id, int $sort): void
    {
        ProposalFeatureItem::query()->findOrFail($id)->update(['sort' => max(0, $sort)]);
        unset($this->items);
    }

    public function delete(string $token): void
    {
        $id = ProposalFeatureItem::validateDeleteToken($token);
        abort_unless($id, 400);
        $item = ProposalFeatureItem::query()->findOrFail($id);
        $item->delete();
        SynAuth::logActivity(Auth::id(), 'pm.master.proposal_feature.delete', 'Deleted proposal feature '.$item->name, 'master.proposal_feature', 'pm');
        unset($this->items);
        $this->dispatch('synapse-confirmed');
    }

    #[Computed]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(label: __('pm::menu.be.parent'), icon: 'fa-solid fa-list')
            ->add(label: __('pm::menu.be.master.parent'), icon: 'fa-solid fa-table')
            ->add(label: __('pm::menu.be.master.proposal_feature'), icon: 'fa-solid fa-list-check');
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
