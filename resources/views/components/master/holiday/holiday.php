<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Models\Holiday;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;
use VmEngine\SynAuth\Facades\SynAuth;

new class extends Component
{
    public string $newDate = '';

    public string $newName = '';

    public function mount(): void
    {
        synav()->setActiveMenu('pm.master.holiday');
    }

    public function title(): string
    {
        return __('pm::proposal.holiday_title');
    }

    /**
     * @return Collection<int, Holiday>
     */
    #[Computed]
    public function holidays(): Collection
    {
        return Holiday::query()->orderByDesc('date')->get();
    }

    public function store(): void
    {
        $this->validate([
            'newDate' => 'required|date',
            'newName' => 'required|string|max:255',
        ]);
        // whereDate: a plain unique rule misses "Y-m-d 00:00:00" values on some drivers
        if (Holiday::query()->whereDate('date', $this->newDate)->exists()) {
            $this->addError('newDate', __('validation.unique', ['attribute' => __('pm::proposal.holiday_date')]));

            return;
        }
        $holiday = Holiday::create(['date' => $this->newDate, 'name' => trim($this->newName)]);
        SynAuth::logActivity(Auth::id(), 'pm.master.holiday.create', 'Created holiday '.$holiday->date->toDateString().' '.$holiday->name, 'master.holiday', 'pm');
        $this->reset('newDate', 'newName');
        unset($this->holidays);
        $this->dispatch('notify', variant: 'success', title: __('pm::proposal.holiday_title'), message: __('pm::proposal.master_saved'));
    }

    public function delete(string $token): void
    {
        $id = Holiday::validateDeleteToken($token);
        abort_unless($id, 400);
        $holiday = Holiday::query()->findOrFail($id);
        $holiday->delete();
        SynAuth::logActivity(Auth::id(), 'pm.master.holiday.delete', 'Deleted holiday '.$holiday->date->toDateString(), 'master.holiday', 'pm');
        unset($this->holidays);
        $this->dispatch('synapse-confirmed');
        $this->dispatch('notify', variant: 'success', title: __('pm::proposal.holiday_title'), message: __('pm::proposal.master_deleted'));
    }

    #[Computed]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(label: __('pm::menu.be.parent'), icon: 'fa-solid fa-list')
            ->add(label: __('pm::menu.be.master.parent'), icon: 'fa-solid fa-table')
            ->add(label: __('pm::menu.be.master.holiday'), icon: 'fa-solid fa-calendar-xmark');
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
