<?php

declare(strict_types=1);

use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use VmEngine\SynAuth\Models\UserActivity;

new class extends Component
{
    use WithPagination;

    public int $projectId;

    public int $perPage = 25;

    public ?UserActivity $selectedActivity = null;

    public function updated(): void
    {
        $this->resetPage();
    }

    #[Computed()]
    public function activities()
    {
        return UserActivity::query()->with('user:id,name,email')
            ->where('module', 'pm')
            ->whereLike('feature', 'project.'.$this->projectId.'%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage)
            ->onEachSide(1);
    }

    public function showDetail(int $activityId): void
    {
        $this->selectedActivity = UserActivity::with('user')->find($activityId);
    }

    #[On('close-modal-activity-detail')]
    public function resetDetail(): void
    {
        $this->selectedActivity = null;
    }
};
