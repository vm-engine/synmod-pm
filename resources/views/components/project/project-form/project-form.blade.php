<div
    class="relative overflow-y-auto"
    x-data="{ pageName: '{{ __('pm::project.create_title') }}', isHome: false }"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <div class="mb-16 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- LEFT COLUMN -->
        <div class="space-y-6">
            <!-- Basic Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.basic_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box required">
                        <label for="name">{{ __('pm::project.name') }}</label>
                        <input
                            class="form-input @error('form.name') has-error @enderror"
                            id="name"
                            name="name"
                            type="text"
                            placeholder="{{ __('pm::project.placeholder_name') }}"
                            wire:model="form.name"
                        />
                        @error('form.name')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="description">{{ __('pm::project.description') }}</label>
                        <textarea
                            class="form-input @error('form.description') has-error @enderror"
                            id="description"
                            name="description"
                            rows="4"
                            placeholder="{{ __('pm::project.placeholder_description') }}"
                            wire:model="form.description"
                        ></textarea>
                        @error('form.description')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="year">{{ __('pm::project.year') }}</label>
                        <input
                            class="form-input @error('form.year') has-error @enderror"
                            id="year"
                            name="year"
                            type="number"
                            min="2000"
                            max="2100"
                            wire:model="form.year"
                        />
                        @error('form.year')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="project_category_id">{{ __('pm::project.project_category') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.project_category_id"
                            :options="$this->projectCategories"
                            placeholder="{{ __('pm::project.placeholder_project_category') }}"
                        />
                        @error('form.project_category_id')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="division_id">{{ __('pm::project.division') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.division_id"
                            :options="$this->divisions"
                            placeholder="{{ __('pm::project.placeholder_division') }}"
                        />
                        @error('form.division_id')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    @if ($form->division_id)
                        <div
                            class="form-box"
                            wire:key="sub-division-{{ $form->division_id }}"
                        >
                            <label for="sub_division">{{ __('pm::project.sub_division') }}</label>

                            {{-- Debug info (remove after testing) --}}
                            <div class="mb-2 text-xs text-gray-600">
                                Division ID: {{ $form->division_id }} |
                                Sub-divisions count: {{ count($this->subDivisions) }}
                                @if (count($this->subDivisions) === 0)
                                    <span class="text-red-600">(No sub-divisions found for this division)</span>
                                @endif
                            </div>

                            <x-synapse-adv-select
                                wire-model="form.sub_division"
                                :options="$this->subDivisions"
                                :multiple="true"
                                placeholder="{{ __('pm::project.placeholder_sub_division') }}"
                            />
                            <small class="text-gray-500">{{ __('pm::project.help_sub_division') }}</small>
                            @error('form.sub_division')
                                <p class="form-error-message">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="form-box">
                            <label for="sub_division">{{ __('pm::project.sub_division') }}</label>
                            <div class="form-input bg-gray-50 text-gray-500 cursor-not-allowed">
                                {{ __('pm::project.select_division_first') }}
                            </div>
                            <small class="text-gray-500">{{ __('pm::project.help_sub_division') }}</small>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Client & Services Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.client_services') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box required">
                        <label for="client_id">{{ __('pm::project.client') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.client_id"
                            :options="$this->initialClient ? [$this->initialClient] : []"
                            :searchable="true"
                            search-method="searchClients"
                            placeholder="{{ __('pm::project.placeholder_client') }}"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_client_search') }}</small>
                        @error('form.client_id')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="client_pic">{{ __('pm::project.client_pic') }}</label>
                        <input
                            class="form-input @error('form.client_pic') has-error @enderror"
                            id="client_pic"
                            name="client_pic"
                            type="text"
                            placeholder="{{ __('pm::project.placeholder_client_pic') }}"
                            wire:model="form.client_pic"
                        />
                        @error('form.client_pic')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="client_pic_phone">{{ __('pm::project.client_pic_phone') }}</label>
                        <input
                            class="form-input @error('form.client_pic_phone') has-error @enderror"
                            id="client_pic_phone"
                            name="client_pic_phone"
                            type="text"
                            placeholder="{{ __('pm::project.placeholder_client_pic_phone') }}"
                            wire:model="form.client_pic_phone"
                        />
                        @error('form.client_pic_phone')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Services field removed — superseded by project_category_id
                    <div class="form-box">
                        <label for="services">{{ __('pm::project.services') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.services"
                            :options="$this->services"
                            :multiple="true"
                            placeholder="{{ __('pm::project.placeholder_services') }}"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_services') }}</small>
                        @error('form.services')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    --}}

                    <div class="form-box">
                        <label for="source_of_sales">{{ __('pm::project.source_of_sales') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.source_of_sales"
                            :options="$this->salesSources"
                            placeholder="{{ __('pm::project.placeholder_source_of_sales') }}"
                        />
                        @error('form.source_of_sales')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Financial Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.financial_info') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="nominal">{{ __('pm::project.nominal') }}</label>
                        <x-synapse-currency
                            id="nominal"
                            name="nominal"
                            currency="Rp."
                            wire:model="form.nominal"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_nominal') }}</small>
                        @error('form.nominal')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="budget">{{ __('pm::project.budget') }}</label>
                        <x-synapse-currency
                            id="budget"
                            name="budget"
                            currency="Rp."
                            wire:model="form.budget"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_budget') }}</small>
                        @error('form.budget')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="down_payment">{{ __('pm::project.down_payment') }}</label>
                        <x-synapse-currency
                            id="down_payment"
                            name="down_payment"
                            currency="Rp."
                            wire:model="form.down_payment"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_down_payment') }}</small>
                        @error('form.down_payment')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="total_terms">{{ __('pm::project.total_terms') }}</label>
                        <input
                            class="form-input @error('form.total_terms') has-error @enderror"
                            id="total_terms"
                            name="total_terms"
                            type="number"
                            min="1"
                            max="100"
                            placeholder="{{ __('pm::project.placeholder_total_terms') }}"
                            wire:model="form.total_terms"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_total_terms') }}</small>
                        @error('form.total_terms')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="payment_terms">{{ __('pm::project.payment_terms') }}</label>
                        <textarea
                            class="form-input @error('form.payment_terms') has-error @enderror"
                            id="payment_terms"
                            name="payment_terms"
                            rows="3"
                            placeholder="{{ __('pm::project.placeholder_payment_terms') }}"
                            wire:model="form.payment_terms"
                        ></textarea>
                        <small class="text-gray-500">{{ __('pm::project.help_payment_terms') }}</small>
                        @error('form.payment_terms')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN -->
        <div class="space-y-6">
            <!-- Status & Progress Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.status_progress') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box required">
                        <label for="status">{{ __('pm::project.status') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.status"
                            :options="$this->statuses"
                            placeholder="{{ __('pm::project.placeholder_status') }}"
                        />
                        @error('form.status')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="progress">{{ __('pm::project.progress') }} ({{ $form->progress }}%)</label>
                        <input
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700"
                            id="progress"
                            name="progress"
                            type="range"
                            min="0"
                            max="100"
                            step="1"
                            wire:model.live="form.progress"
                        />
                        <div class="flex justify-between text-xs text-gray-500 mt-1">
                            <span>0%</span>
                            <span>25%</span>
                            <span>50%</span>
                            <span>75%</span>
                            <span>100%</span>
                        </div>
                        <small class="text-gray-500">{{ __('pm::project.help_progress') }}</small>
                        @error('form.progress')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="priority">{{ __('pm::project.priority') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.priority"
                            :options="$this->priorities"
                            placeholder="{{ __('pm::project.placeholder_priority') }}"
                        />
                        @error('form.priority')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="risk_level">{{ __('pm::project.risk_level') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.risk_level"
                            :options="$this->riskLevels"
                            placeholder="{{ __('pm::project.placeholder_risk_level') }}"
                        />
                        @error('form.risk_level')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Schedule Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.schedule') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="project_start">{{ __('pm::project.project_start') }}</label>
                        <x-synapse-datepicker
                            id="project_start"
                            name="project_start"
                            wire:model="form.project_start"
                        />
                        @error('form.project_start')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="project_end">{{ __('pm::project.project_end') }}</label>
                        <x-synapse-datepicker
                            id="project_end"
                            name="project_end"
                            wire:model="form.project_end"
                        />
                        @error('form.project_end')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="planned_delivery_date">{{ __('pm::project.planned_delivery_date') }}</label>
                        <x-synapse-datepicker
                            id="planned_delivery_date"
                            name="planned_delivery_date"
                            wire:model="form.planned_delivery_date"
                        />
                        @error('form.planned_delivery_date')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="actual_delivery_date">{{ __('pm::project.actual_delivery_date') }}</label>
                        <x-synapse-datepicker
                            id="actual_delivery_date"
                            name="actual_delivery_date"
                            wire:model="form.actual_delivery_date"
                        />
                        @error('form.actual_delivery_date')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="contract_signed_at">{{ __('pm::project.contract_signed_at') }}</label>
                        <x-synapse-datepicker
                            id="contract_signed_at"
                            name="contract_signed_at"
                            wire:model="form.contract_signed_at"
                        />
                        @error('form.contract_signed_at')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="client_approved_at">{{ __('pm::project.client_approved_at') }}</label>
                        <x-synapse-datepicker
                            id="client_approved_at"
                            name="client_approved_at"
                            wire:model="form.client_approved_at"
                        />
                        @error('form.client_approved_at')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="warranty_end_date">{{ __('pm::project.warranty_end_date') }}</label>
                        <x-synapse-datepicker
                            id="warranty_end_date"
                            name="warranty_end_date"
                            wire:model="form.warranty_end_date"
                        />
                        @error('form.warranty_end_date')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Team & Management Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.team_management') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="manager">{{ __('pm::project.manager') }}</label>
                        <input
                            class="form-input @error('form.manager') has-error @enderror"
                            id="manager"
                            name="manager"
                            type="text"
                            placeholder="{{ __('pm::project.placeholder_manager') }}"
                            wire:model="form.manager"
                        />
                        @error('form.manager')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Hidden for now --}}
                    {{--
                    <div class="form-box">
                        <label for="project_manager_id">{{ __('pm::project.project_manager') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.project_manager_id"
                            :options="$this->users"
                            placeholder="{{ __('pm::project.placeholder_project_manager') }}"
                        />
                        @error('form.project_manager_id')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="assigned_team">{{ __('pm::project.assigned_team') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.assigned_team"
                            :options="$this->users"
                            :multiple="true"
                            placeholder="{{ __('pm::project.placeholder_assigned_team') }}"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_assigned_team') }}</small>
                        @error('form.assigned_team')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                    --}}
                </div>
            </div>

            <!-- Technical Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.technical_info') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="url">{{ __('pm::project.url') }}</label>
                        <input
                            class="form-input @error('form.url') has-error @enderror"
                            id="url"
                            name="url"
                            type="url"
                            placeholder="{{ __('pm::project.placeholder_url') }}"
                            wire:model="form.url"
                        />
                        @error('form.url')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="staging_url">{{ __('pm::project.staging_url') }}</label>
                        <input
                            class="form-input @error('form.staging_url') has-error @enderror"
                            id="staging_url"
                            name="staging_url"
                            type="url"
                            placeholder="{{ __('pm::project.placeholder_url') }}"
                            wire:model="form.staging_url"
                        />
                        @error('form.staging_url')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="production_url">{{ __('pm::project.production_url') }}</label>
                        <input
                            class="form-input @error('form.production_url') has-error @enderror"
                            id="production_url"
                            name="production_url"
                            type="url"
                            placeholder="{{ __('pm::project.placeholder_url') }}"
                            wire:model="form.production_url"
                        />
                        @error('form.production_url')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="repository_url">{{ __('pm::project.repository_url') }}</label>
                        <input
                            class="form-input @error('form.repository_url') has-error @enderror"
                            id="repository_url"
                            name="repository_url"
                            type="url"
                            placeholder="{{ __('pm::project.placeholder_url') }}"
                            wire:model="form.repository_url"
                        />
                        @error('form.repository_url')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Additional Information Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::project.additional_info') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="maintenance_type">{{ __('pm::project.maintenance_type') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.maintenance_type"
                            :options="$this->maintenanceTypes"
                            placeholder="{{ __('pm::project.placeholder_maintenance_type') }}"
                        />
                        @error('form.maintenance_type')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="quality_score">{{ __('pm::project.quality_score') }} (1-5)</label>
                        <input
                            class="form-input @error('form.quality_score') has-error @enderror"
                            id="quality_score"
                            name="quality_score"
                            type="number"
                            min="1"
                            max="5"
                            wire:model="form.quality_score"
                        />
                        @error('form.quality_score')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="client_satisfaction">{{ __('pm::project.client_satisfaction') }} (1-5)</label>
                        <input
                            class="form-input @error('form.client_satisfaction') has-error @enderror"
                            id="client_satisfaction"
                            name="client_satisfaction"
                            type="number"
                            min="1"
                            max="5"
                            wire:model="form.client_satisfaction"
                        />
                        @error('form.client_satisfaction')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="tags">{{ __('pm::project.tags') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.tags"
                            :options="[]"
                            :multiple="true"
                            :taggable="true"
                            placeholder="{{ __('pm::project.placeholder_tags') }}"
                        />
                        <small class="text-gray-500">{{ __('pm::project.help_tags') }}</small>
                        @error('form.tags')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Action Buttons -->
    <div class="card z-9 fixed bottom-0 right-0 m-5 shadow-gray-700">
        <button
            class="btn primary"
            type="button"
            wire:click="save"
            wire:loading.attr="disabled"
            wire:target="save"
        >
            <span
                wire:loading.remove
                wire:target="save"
            >{{ __('pm::project.save') }}</span>
            <span
                wire:loading
                wire:target="save"
            >
                <span class="fa-solid fa-spinner fa-spin mr-1"></span>
                {{ __('pm::project.save') }}
            </span>
        </button>
        <a
            class="btn secondary"
            href="{{ backend_route('pm.project.index') }}"
            wire:navigate
        >
            {{ __('pm::project.cancel') }}
        </a>
    </div>
</div>
