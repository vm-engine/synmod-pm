<?php

declare(strict_types=1);

namespace VmEngine\Pm\Form;

use Illuminate\Validation\Rule;
use Livewire\Form;
use VmEngine\Pm\Enums\MaintenanceType;
use VmEngine\Pm\Enums\ProjectPriority;
use VmEngine\Pm\Enums\ProjectStatus;
use VmEngine\Pm\Enums\RiskLevel;
use VmEngine\Pm\Enums\SalesSources;
use VmEngine\Pm\Models\Project;
use VmEngine\Pm\Services\ProjectService;

class ProjectFormObject extends Form
{
    public ?Project $project = null;

    // Basic Information
    public string $name = '';

    public string $description = '';

    public int $year = 0;

    public ?int $division_id = null;

    public ?int $project_category_id = null;

    /**
     * @var array<int, int|string>
     */
    public array $sub_division = [];

    public ?int $client_id = null;

    // Services & Sales
    // public array $services = [];

    public ?int $source_of_sales = null;

    // Financial
    public string $nominal = '';

    public string $budget = '';

    public string $down_payment = '';

    public int $total_terms = 1;

    public string $payment_terms = '';

    public string $contract_signed_at = '';

    public string $client_approved_at = '';

    // Status & Progress
    public ?int $status = null;

    public int $progress = 0;

    public ?int $priority = null;

    public ?int $risk_level = null;

    // Client Information
    public string $client_pic = '';

    public string $client_pic_phone = '';

    // Dates
    public string $project_start = '';

    public string $project_end = '';

    public string $planned_delivery_date = '';

    public string $actual_delivery_date = '';

    public string $warranty_end_date = '';

    // Team & Management
    public string $manager = '';

    public ?int $project_manager_id = null;

    /**
     * @var array<int, int|string>
     */
    public array $assigned_team = [];

    // Technical
    public string $url = '';

    public string $staging_url = '';

    public string $production_url = '';

    public string $repository_url = '';

    public string $server_info = '';

    // Quality
    public ?int $maintenance_type = null;

    public ?int $quality_score = null;

    public ?int $client_satisfaction = null;

    /**
     * @var array<int, string>
     */
    public array $tags = [];

    /**
     * Load existing project data into the form.
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;

        // Basic Information
        $this->name = $project->name ?? '';
        $this->description = $project->description ?? '';
        $this->year = $project->year;
        $this->division_id = $project->division_id;
        $this->project_category_id = $project->project_category_id;
        $this->sub_division = $project->sub_division ?? [];
        $this->client_id = $project->client_id;

        // Services & Sales
        // $this->services = $project->services ?? [];
        $this->source_of_sales = $project->source_of_sales?->value;

        // Financial - format for display with 2 decimals (will be cleaned on update/save)
        $this->nominal = $project->nominal ? number_format((float) $project->nominal, 2, ',', '.') : '';
        $this->budget = $project->budget ? number_format((float) $project->budget, 2, ',', '.') : '';
        $this->down_payment = $project->down_payment ? number_format((float) $project->down_payment, 2, ',', '.') : '';
        $this->total_terms = $project->total_terms ?? 1;
        $this->payment_terms = $project->payment_terms ?? '';
        $this->contract_signed_at = $project->contract_signed_at?->format('Y-m-d') ?? '';
        $this->client_approved_at = $project->client_approved_at?->format('Y-m-d') ?? '';

        // Status & Progress
        $this->status = $project->status->value ?? ProjectStatus::PROSPECT->value;
        $this->progress = $project->progress ?? 0;
        $this->priority = $project->priority->value ?? ProjectPriority::MEDIUM->value;
        $this->risk_level = $project->risk_level?->value;

        // Client Information
        $this->client_pic = $project->client_pic ?? '';
        $this->client_pic_phone = $project->client_pic_phone ?? '';

        // Dates
        $this->project_start = $project->project_start?->format('Y-m-d') ?? '';
        $this->project_end = $project->project_end?->format('Y-m-d') ?? '';
        $this->planned_delivery_date = $project->planned_delivery_date?->format('Y-m-d') ?? '';
        $this->actual_delivery_date = $project->actual_delivery_date?->format('Y-m-d') ?? '';
        $this->warranty_end_date = $project->warranty_end_date?->format('Y-m-d') ?? '';

        // Team & Management
        $this->manager = $project->manager ?? '';
        $this->project_manager_id = $project->project_manager_id;
        $this->assigned_team = $project->assigned_team ?? [];

        // Technical
        $this->url = $project->url ?? '';
        $this->staging_url = $project->staging_url ?? '';
        $this->production_url = $project->production_url ?? '';
        $this->repository_url = $project->repository_url ?? '';
        $this->server_info = ! empty($project->server_info) ? json_encode($project->server_info, JSON_THROW_ON_ERROR) : '';

        // Quality
        $this->maintenance_type = $project->maintenance_type?->value;
        $this->quality_score = $project->quality_score;
        $this->client_satisfaction = $project->client_satisfaction;
        $this->tags = $project->tags ?? [];
    }

    /**
     * Clean currency values before validation.
     */
    protected function cleanCurrencyFields(): void
    {
        // Clean currency formatting from nominal, budget, and down_payment
        foreach (['nominal', 'budget', 'down_payment'] as $field) {
            if (! empty($this->{$field})) {
                $cleaned = preg_replace('/[^\d,.-]/', '', $this->{$field});
                $cleaned = str_replace(['.', ','], ['', '.'], $cleaned);
                $this->{$field} = $cleaned ?: '';
            }
        }
    }

    /**
     * Get validation rules.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        // Clean currency fields before validation
        $this->cleanCurrencyFields();

        return [
            // Basic Information
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'year' => 'required|integer|min:2000|max:2100',
            'division_id' => 'required|exists:divisions,id',
            'project_category_id' => 'required|exists:project_categories,id',
            'sub_division' => 'nullable|array',
            'sub_division.*' => 'integer|exists:sub_divisions,id',
            'client_id' => 'required|exists:clients,id',

            // Services & Sales
            // 'services' => 'nullable|array',
            // 'services.*' => ['integer', Rule::enum(Services::class)],
            'source_of_sales' => ['nullable', 'integer', Rule::enum(SalesSources::class)],

            // Financial
            'nominal' => 'nullable|numeric|min:0',
            'budget' => 'nullable|numeric|min:0',
            'down_payment' => 'nullable|numeric|min:0',
            'total_terms' => 'required|integer|min:1|max:100',
            'payment_terms' => 'nullable|string',
            'contract_signed_at' => 'nullable|date',
            'client_approved_at' => 'nullable|date',

            // Status & Progress
            'status' => ['nullable', 'integer', Rule::enum(ProjectStatus::class)],
            'progress' => 'required|integer|min:0|max:100',
            'priority' => ['nullable', 'integer', Rule::enum(ProjectPriority::class)],
            'risk_level' => ['nullable', 'integer', Rule::enum(RiskLevel::class)],

            // Client Information
            'client_pic' => 'nullable|string|max:255',
            'client_pic_phone' => 'nullable|string|max:20',

            // Dates
            'project_start' => 'nullable|date',
            'project_end' => 'nullable|date|after_or_equal:project_start',
            'planned_delivery_date' => 'nullable|date',
            'actual_delivery_date' => 'nullable|date',
            'warranty_end_date' => 'nullable|date',

            // Team & Management
            'manager' => 'nullable|string|max:255',
            'project_manager_id' => 'nullable|exists:users,id',
            'assigned_team' => 'nullable|array',
            'assigned_team.*' => 'integer|exists:users,id',

            // Technical
            'url' => 'nullable|url|max:2000',
            'staging_url' => 'nullable|url|max:2000',
            'production_url' => 'nullable|url|max:2000',
            'repository_url' => 'nullable|url|max:2000',
            'server_info' => 'nullable|string',

            // Quality
            'maintenance_type' => ['nullable', 'integer', Rule::enum(MaintenanceType::class)],
            'quality_score' => 'nullable|integer|min:1|max:5',
            'client_satisfaction' => 'nullable|integer|min:1|max:5',
            'tags' => 'nullable|array',
        ];
    }

    /**
     * Get custom validation messages.
     *
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => __('pm::project.validation.name_required'),
            'year.required' => __('pm::project.validation.year_required'),
            'year.min' => __('pm::project.validation.year_min'),
            'year.max' => __('pm::project.validation.year_max'),
            'division_id.required' => __('pm::project.validation.division_required'),
            'division_id.exists' => __('pm::project.validation.division_exists'),
            'project_category_id.required' => __('pm::project.validation.project_category_required'),
            'client_id.required' => __('pm::project.validation.client_required'),
            'project_end.after_or_equal' => __('pm::project.validation.project_end_after_start'),
            'nominal.numeric' => __('pm::project.validation.nominal_numeric'),
            'budget.numeric' => __('pm::project.validation.budget_numeric'),
            'progress.min' => __('pm::project.validation.progress_min'),
            'progress.max' => __('pm::project.validation.progress_max'),
        ];
    }

    /**
     * Create a new project.
     */
    public function store(): void
    {
        $this->validate();

        $projectService = app(ProjectService::class);

        $projectData = $this->prepareProjectData();

        $projectService->create($projectData);

        $this->reset();
    }

    /**
     * Update existing project.
     */
    public function update(): void
    {
        $this->validate();

        $projectService = app(ProjectService::class);

        $projectData = $this->prepareProjectData();

        $this->project = $projectService->update($this->project, $projectData);
    }

    /**
     * Prepare project data array from form fields.
     *
     * @return array<string, mixed>
     */
    private function prepareProjectData(): array
    {
        $projectService = app(ProjectService::class);

        return [
            'division_id' => $this->division_id,
            'project_category_id' => $this->project_category_id,
            'client_id' => $this->client_id,
            'project_manager_id' => $this->project_manager_id,
            'name' => $this->name,
            'description' => $this->description,
            'year' => $this->year ?: (int) date('Y'),
            'sub_division' => $this->sub_division,
            // 'services' => $this->services,
            'source_of_sales' => $this->source_of_sales ?? SalesSources::WEB->value,
            'nominal' => $this->nominal ?: null,
            'budget' => $this->budget ?: null,
            'down_payment' => $this->down_payment ?: null,
            'total_terms' => $this->total_terms,
            'payment_terms' => $this->payment_terms,
            'contract_signed_at' => $this->contract_signed_at ?: null,
            'client_approved_at' => $this->client_approved_at ?: null,
            'url' => $this->url,
            'staging_url' => $this->staging_url,
            'production_url' => $this->production_url,
            'repository_url' => $this->repository_url,
            'status' => $this->status ?? ProjectStatus::PROSPECT->value,
            'progress' => $this->progress,
            'priority' => $this->priority ?? ProjectPriority::MEDIUM->value,
            'risk_level' => $this->risk_level,
            'client_pic' => $this->client_pic,
            'client_pic_phone' => $this->client_pic_phone,
            'project_start' => $this->project_start ?: null,
            'project_end' => $this->project_end ?: null,
            'planned_delivery_date' => $this->planned_delivery_date ?: null,
            'actual_delivery_date' => $this->actual_delivery_date ?: null,
            'warranty_end_date' => $this->warranty_end_date ?: null,
            'manager' => $this->manager,
            'assigned_team' => $this->assigned_team,
            'tags' => $this->tags,
            'server_info' => $projectService->parseServerInfo($this->server_info),
            'maintenance_type' => $this->maintenance_type,
            'quality_score' => $this->quality_score,
            'client_satisfaction' => $this->client_satisfaction,
        ];
    }
}
