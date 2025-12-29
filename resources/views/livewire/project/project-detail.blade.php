<div x-data="{ pageName: '{{ $project->name }}', isHome: false, activeTab: '{{ $activeTab }}' }">
    @include('synapps::components.layouts.partials.breadcrumbs')

    <!-- Project Header -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                </div>
                <div class="flex gap-2">
                    @canAccess('pm.project.update')
                    <a
                        class="btn primary"
                        href="{{ backend_route('pm.project.edit', ['id' => $project->id]) }}"
                        wire:navigate
                    >
                        <span class="fa-solid fa-edit"></span> {{ __('pm::project.edit_project') }}
                    </a>
                    @endcanAccess
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-4">
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('pm::project.status') }}</div>
                    <div class="mt-1 text-lg font-semibold">
                        @php
                            $statusColor = $project->status ? $project->status->color() : 'gray';
                        @endphp
                        <span
                            class="bg-{{ $statusColor }}-100 text-{{ $statusColor }}-800 dark:bg-{{ $statusColor }}-900/30 dark:text-{{ $statusColor }}-300 inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium"
                        >
                            {{ $project->status ? $project->status->label() : '-' }}
                        </span>
                    </div>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('pm::project.progress') }}</div>
                    <div class="mt-1 text-lg font-semibold">{{ $project->progress }}%</div>
                    <div class="mt-2 h-2.5 w-full rounded-full bg-gray-200 dark:bg-gray-700">
                        <div
                            class="h-2.5 rounded-full bg-blue-600"
                            style="width: {{ $project->progress }}%"
                        ></div>
                    </div>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('pm::project.priority') }}</div>
                    <div class="mt-1 text-lg font-semibold">
                        @php
                            $priorityColor = $project->priority ? $project->priority->color() : 'gray';
                        @endphp
                        <span
                            class="bg-{{ $priorityColor }}-100 text-{{ $priorityColor }}-800 dark:bg-{{ $priorityColor }}-900/30 dark:text-{{ $priorityColor }}-300 inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-sm font-medium"
                        >
                            {{ $project->priority ? $project->priority->label() : '-' }}
                        </span>
                    </div>
                </div>
                <div class="rounded-lg bg-gray-50 p-4 dark:bg-gray-800">
                    <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('pm::project.budget_status') }}</div>
                    <div class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                        Rp {{ number_format($project->nominal ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabbed Interface -->
    <div>
        <!-- Tab Navigation -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <nav class="-mb-px flex space-x-8">
                <button
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                    @click="activeTab = 'overview'"
                    :class="activeTab === 'overview' ? 'border-brand-500 text-brand-600 dark:text-brand-400' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    <span class="fa-solid fa-info-circle mr-2"></span>
                    {{ __('pm::project.tab_overview') }}
                </button>
                <button
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                    @click="activeTab = 'documents'"
                    :class="activeTab === 'documents' ? 'border-brand-500 text-brand-600 dark:text-brand-400' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    <span class="fa-solid fa-file mr-2"></span>
                    {{ __('pm::project.tab_documents') }}
                </button>
                <button
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                    @click="activeTab = 'timeline'"
                    :class="activeTab === 'timeline' ? 'border-brand-500 text-brand-600 dark:text-brand-400' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    <span class="fa-solid fa-clock mr-2"></span>
                    {{ __('pm::project.tab_timeline') }}
                </button>
                <button
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                    @click="activeTab = 'financial'"
                    :class="activeTab === 'financial' ? 'border-brand-500 text-brand-600 dark:text-brand-400' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    <span class="fa-solid fa-money-bill mr-2"></span>
                    {{ __('pm::project.tab_financial') }}
                </button>
                <button
                    class="whitespace-nowrap border-b-2 px-1 py-4 text-sm font-medium transition-colors"
                    @click="activeTab = 'team'"
                    :class="activeTab === 'team' ? 'border-brand-500 text-brand-600 dark:text-brand-400' :
                        'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                >
                    <span class="fa-solid fa-users mr-2"></span>
                    {{ __('pm::project.tab_team') }}
                </button>
            </nav>
        </div>

        <!-- Tab Content -->

        <!-- Overview Tab -->
        <div
            class="space-y-6"
            x-show="activeTab === 'overview'"
        >
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Project Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.project_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.division') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $project->division?->name ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">{{ __('pm::project.year') }}:
                                </dt>
                                <dd class="text-gray-900 dark:text-white">{{ $project->year }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.services') }}:</dt>
                                <dd>
                                    <div class="flex flex-wrap gap-1">
                                        @if (!empty($project->services))
                                            @foreach ($project->services as $serviceId)
                                                @php
                                                    $service = \VmEngine\Pm\Enums\Services::tryFrom($serviceId);
                                                @endphp
                                                @if ($service)
                                                    <span
                                                        class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800 dark:bg-blue-900/30 dark:text-blue-300"
                                                    >
                                                        {{ $service->label() }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </div>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.source_of_sales') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->source_of_sales ? $project->source_of_sales->label() : '-' }}</dd>
                            </div>
                            @if (!empty($project->tags))
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">
                                        {{ __('pm::project.tags') }}:</dt>
                                    <dd>
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($project->tags as $tag)
                                                <span
                                                    class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-medium text-gray-800 dark:bg-gray-800 dark:text-gray-300"
                                                >
                                                    {{ $tag }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Client Information Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.client_information') }}</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.client') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $project->client?->company ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.client_pic') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $project->client_pic ?? '-' }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.client_pic_phone') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">{{ $project->client_pic_phone ?? '-' }}</dd>
                            </div>
                            @if ($project->client_satisfaction)
                                <div class="flex justify-between">
                                    <dt class="font-medium text-gray-600 dark:text-gray-400">
                                        {{ __('pm::project.client_satisfaction') }}:</dt>
                                    <dd class="text-gray-900 dark:text-white">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span
                                                class="{{ $i <= $project->client_satisfaction ? 'text-yellow-400' : 'text-gray-300' }} fa-solid fa-star"
                                            ></span>
                                        @endfor
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Schedule Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.schedule_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.start_date') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->project_start?->format('d M Y') ?? __('pm::project.no_date_set') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.end_date') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->project_end?->format('d M Y') ?? __('pm::project.no_date_set') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.planned_delivery_date') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->planned_delivery_date?->format('d M Y') ?? __('pm::project.no_date_set') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.actual_delivery_date') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->actual_delivery_date?->format('d M Y') ?? __('pm::project.not_yet_delivered') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.contract_signed_at') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->contract_signed_at?->format('d M Y') ?? __('pm::project.no_date_set') }}
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.warranty_end_date') }}:</dt>
                                <dd class="text-gray-900 dark:text-white">
                                    {{ $project->warranty_end_date?->format('d M Y') ?? __('pm::project.no_date_set') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Technical URLs Card -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.technical_urls') }}</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-3 text-sm">
                            <div>
                                <dt class="mb-1 font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.production') }}:</dt>
                                <dd>
                                    @if ($project->production_url)
                                        <a
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 break-all"
                                            href="{{ $project->production_url }}"
                                            target="_blank"
                                        >
                                            <span
                                                class="fa-solid fa-external-link mr-1"></span>{{ $project->production_url }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="mb-1 font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.staging') }}:</dt>
                                <dd>
                                    @if ($project->staging_url)
                                        <a
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 break-all"
                                            href="{{ $project->staging_url }}"
                                            target="_blank"
                                        >
                                            <span
                                                class="fa-solid fa-external-link mr-1"></span>{{ $project->staging_url }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="mb-1 font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.repository') }}:</dt>
                                <dd>
                                    @if ($project->repository_url)
                                        <a
                                            class="text-brand-600 hover:text-brand-700 dark:text-brand-400 break-all"
                                            href="{{ $project->repository_url }}"
                                            target="_blank"
                                        >
                                            <span
                                                class="fa-solid fa-external-link mr-1"></span>{{ $project->repository_url }}
                                        </a>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Tab -->
        <div x-show="activeTab === 'timeline'">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.timeline_activity') }}</h3>
                </div>
                <div class="card-body">
                    <div class="py-8 text-center text-gray-500">
                        <span class="fa-solid fa-clock mb-4 block text-4xl text-gray-300"></span>
                        <p>{{ __('pm::project.timeline_placeholder') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Tab -->
        <div x-show="activeTab === 'documents'">
            @livewire('pm.project.document-manager', ['project' => $project], key('document-manager-' . $project->id))
        </div>

        <!-- Financial Tab -->
        <div x-show="activeTab === 'financial'">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.financial_info') }}</h3>
                    </div>
                    <div class="card-body">
                        <dl class="space-y-4">
                            <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.budget') }}:</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->financialSummary['budget'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.nominal') }}:</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->financialSummary['nominal'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.budget_variance') }}:</dt>
                                <dd
                                    class="{{ $this->financialSummary['budget_variance'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }} font-semibold">
                                    Rp
                                    {{ number_format(abs($this->financialSummary['budget_variance']), 0, ',', '.') }}
                                    <span class="text-sm">
                                        @if ($this->financialSummary['budget_variance'] >= 0)
                                            ({{ __('pm::project.under_budget') }})
                                        @elseif ($this->financialSummary['budget_variance'] < 0)
                                            ({{ __('pm::project.over_budget') }})
                                        @endif
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between border-b border-gray-200 pb-3 dark:border-gray-700">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.down_payment') }}:</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->financialSummary['down_payment'], 0, ',', '.') }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-600 dark:text-gray-400">
                                    {{ __('pm::project.remaining_balance') }}:</dt>
                                <dd class="font-semibold text-gray-900 dark:text-white">Rp
                                    {{ number_format($this->financialSummary['remaining'], 0, ',', '.') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('pm::project.payment_terms') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="whitespace-pre-line text-sm text-gray-700 dark:text-gray-300">
                            {{ $project->payment_terms ?? __('pm::project.no_payment_terms') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Team Tab -->
        <div x-show="activeTab === 'team'">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.tab_team') }}</h3>
                </div>
                <div class="card-body">
                    <div class="space-y-6">
                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">
                                {{ __('pm::project.project_manager_label') }}</h4>
                            <div class="flex items-center gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                <div class="flex-shrink-0">
                                    <div
                                        class="bg-brand-100 dark:bg-brand-900/30 flex h-10 w-10 items-center justify-center rounded-full">
                                        <span class="fa-solid fa-user text-brand-600 dark:text-brand-400"></span>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $project->projectManager?->name ?? ($project->manager ?? '-') }}
                                    </p>
                                    @if ($project->projectManager)
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $project->projectManager->email }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-3 font-semibold text-gray-900 dark:text-white">
                                {{ __('pm::project.team_members_label') }}</h4>
                            @if ($this->teamMembers->isNotEmpty())
                                <div class="grid grid-cols-1 gap-3 md:grid-cols-2">
                                    @foreach ($this->teamMembers as $member)
                                        <div
                                            class="flex items-center gap-3 rounded-lg bg-gray-50 p-3 dark:bg-gray-800">
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 dark:bg-gray-700">
                                                    <span
                                                        class="fa-solid fa-user text-gray-600 dark:text-gray-400"></span>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-white">
                                                    {{ $member->name }}</p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ $member->email }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="py-4 text-center text-gray-500">{{ __('pm::project.no_team_members') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
