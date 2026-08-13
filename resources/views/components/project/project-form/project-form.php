<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Attributes\Computed;
use Livewire\Component;
use VmEngine\Pm\Enums\MaintenanceType;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\RiskLevel;
use VmEngine\Pm\Enums\SalesSources;
use VmEngine\Pm\Form\ProjectFormObject;
use VmEngine\Pm\Models\Client;
use VmEngine\Pm\Models\Division;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Models\ProjectCategory;
use VmEngine\Pm\Models\SubDivision;
use VmEngine\Synapse\Services\Helper\Breadcrumbs;

new class extends Component
{
    public ProjectFormObject $form;

    public function mount(?int $id = null): void
    {
        if ($id) {
            try {
                $project = Project::findOrFail($id);
                $this->form->setProject($project);
            } catch (ModelNotFoundException) {
                session()->flash('danger', __('pm::messages.project_not_found'));
                $this->redirectRoute(backend_route('pm.project.index', nameOnly: true), navigate: true);
            }
        } else {
            // Set defaults for new project
            $this->form->year = (int) date('Y');
            $this->form->status = ProjectStatus::PROSPECT->value;
            $this->form->priority = ProjectPriority::MEDIUM->value;
            $this->form->progress = 0;
        }

        synav()->setActiveMenu('pm.project.index');
    }

    public function title(): string
    {
        return $this->form->project ? __('pm::project.edit_title') : __('pm::project.create_title');
    }

    /**
     * When division changes, clear sub_division selection and refresh component.
     */
    public function updatedFormDivisionId($value): void
    {
        // Clear selected sub-divisions
        $this->form->sub_division = [];

        // Unset the computed property cache to force refresh
        unset($this->subDivisions);
    }

    /**
     * Intercept and clean values before assignment.
     */
    public function updating($name, $value): void
    {
        // Clean currency values (remove dots, convert comma to decimal point)
        if (in_array($name, ['form.nominal', 'form.budget', 'form.down_payment'])) {
            // Remove currency symbol, spaces, and thousand separators
            $cleaned = preg_replace('/[^\d,.-]/', '', $value);
            // Convert Indonesian format to decimal: 1.000.000,00 -> 1000000.00
            $cleaned = str_replace(['.', ','], ['', '.'], $cleaned);
            $this->form->{str_replace('form.', '', $name)} = $cleaned ?: '';
        }
    }

    /**
     * When client is selected, auto-fill PIC and phone.
     */
    public function updatedFormClientId($value): void
    {
        if (! $value) {
            return;
        }

        $client = Client::find($value);
        if ($client) {
            $this->form->client_pic = $client->pic ?? '';
            $this->form->client_pic_phone = $client->phone ?? '';
        }
    }

    /**
     * Search clients for AJAX select (returns limited results with status indicator).
     */
    public function searchClients($search = ''): array
    {
        return Client::query()
            ->when($search, fn ($q) => $q->where('company', 'like', "%{$search}%"))
            ->limit(20)
            ->get()
            ->map(function ($client) {
                $statusLabel = $client->isProspect() ? ' (Prospect)' : ' (Active)';

                return [
                    'value' => $client->id,
                    'label' => $client->company.$statusLabel,
                ];
            })
            ->toArray();
    }

    /**
     * Get filtered sub-divisions based on selected division.
     */
    #[Computed]
    public function subDivisions(): array
    {
        // Only return sub-divisions if a division is selected
        if (! $this->form->division_id) {
            return [];
        }

        $divisionId = (int) $this->form->division_id;

        return SubDivision::where('division_id', $divisionId)
            ->orderBy('name')
            ->get()
            ->map(fn ($item) => [
                'value' => $item->id,
                'label' => $item->name,
            ])
            ->toArray();
    }

    public function save(): void
    {
        if ($this->form->project) {
            $this->form->update();
            session()->flash('success', __('pm::messages.project_updated_successfully'));
        } else {
            $this->form->store();
            session()->flash('success', __('pm::messages.project_created_successfully'));
        }

        $this->redirectRoute(backend_route('pm.project.index', nameOnly: true), navigate: true);
    }

    #[Computed()]
    public function divisions(): array
    {
        return Division::orderBy('id')->get()->map(fn ($d) => [
            'value' => $d->id,
            'label' => $d->name,
        ])->toArray();
    }

    #[Computed()]
    public function projectCategories(): array
    {
        return ProjectCategory::orderBy('id')->get()->map(fn ($pc) => [
            'value' => $pc->id,
            'label' => $pc->name,
        ])->toArray();
    }

    #[Computed()]
    public function initialClient(): ?array
    {
        if ($this->form->client_id) {
            $client = Client::find($this->form->client_id);
            if ($client) {
                $statusLabel = $client->isProspect() ? ' (Prospect)' : ' (Active)';

                return [
                    'value' => $client->id,
                    'label' => $client->company.$statusLabel,
                ];
            }
        }

        return null;
    }

    #[Computed()]
    public function salesSources(): array
    {
        return collect(SalesSources::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
    }

    #[Computed()]
    public function statuses(): array
    {
        return collect(ProjectStatus::cases())->map(fn ($s) => [
            'value' => $s->value,
            'label' => $s->label(),
        ])->toArray();
    }

    #[Computed()]
    public function priorities(): array
    {
        return collect(ProjectPriority::cases())->map(fn ($p) => [
            'value' => $p->value,
            'label' => $p->label(),
        ])->toArray();
    }

    #[Computed()]
    public function maintenanceTypes(): array
    {
        return collect(MaintenanceType::cases())->map(fn ($m) => [
            'value' => $m->value,
            'label' => $m->label(),
        ])->toArray();
    }

    #[Computed()]
    public function riskLevels(): array
    {
        return collect(RiskLevel::cases())->map(fn ($r) => [
            'value' => $r->value,
            'label' => $r->label(),
        ])->toArray();
    }

    #[Computed()]
    public function users(): array
    {
        $UserModel = config('auth.providers.users.model');

        return $UserModel::select('id as value', 'name as label')->get()->toArray();
    }

    #[Computed()]
    public function breadcrumbs(): Breadcrumbs
    {
        return Breadcrumbs::make(
            label: __('pm::menu.be.parent'),
            icon: 'fa-solid fa-list',
        )->add(
            label: __('pm::project.list_title'),
            url: backend_route('pm.project.index'),
            icon: 'fa-solid fa-diagram-project',
        )->add(
            label: $this->form->project ? __('pm::project.edit_title') : __('pm::project.create_title'),
            icon: 'fa-solid fa-pen',
        );
    }

    public function render()
    {
        return $this->view()->title(page_title($this->title()));
    }
};
