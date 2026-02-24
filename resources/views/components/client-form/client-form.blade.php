<div
    class="relative overflow-y-auto"
    x-data="{ pageName: '{{ __('pm::client.form_title') }}', isHome: false }"
>
    @include('synapps::components.layouts.partials.breadcrumbs', ['breadcrumbs' => $this->breadcrumbs])

    <div class="mb-16 grid grid-cols-1 gap-6 sm:grid-cols-2">
        <!-- Left Column: Client Data -->
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::client.client_information') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box">
                        <label for="category">{{ __('pm::client.category') }}</label>
                        <x-synapse-select
                            id="client_category_id"
                            name="client_category_id"
                            :options="$this->categories"
                            wire:model="form.client_category_id"
                        />
                        @error('form.client_category_id')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="company">{{ __('pm::client.company') }}</label>
                        <input
                            class="form-input @error('form.company') has-error @enderror"
                            id="company"
                            name="company"
                            type="text"
                            wire:model.blur="form.company"
                            x-on:input="if (!$wire.form.client) { $wire.form.name = $event.target.value }"
                        />
                        @error('form.company')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="address">{{ __('pm::client.address') }}</label>
                        <textarea
                            class="form-input @error('form.address') has-error @enderror"
                            id="address"
                            name="address"
                            rows="3"
                            wire:model="form.address"
                        ></textarea>
                        @error('form.address')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="city">{{ __('pm::client.city') }}</label>
                        <input
                            class="form-input @error('form.city') has-error @enderror"
                            id="city"
                            name="city"
                            type="text"
                            wire:model="form.city"
                        />
                        @error('form.city')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="director">{{ __('pm::client.director') }}</label>
                        <input
                            class="form-input @error('form.director') has-error @enderror"
                            id="director"
                            name="director"
                            type="text"
                            wire:model="form.director"
                        />
                        @error('form.director')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="pic">{{ __('pm::client.pic') }}</label>
                        <input
                            class="form-input @error('form.pic') has-error @enderror"
                            id="pic"
                            name="pic"
                            type="text"
                            wire:model="form.pic"
                        />
                        @error('form.pic')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="phone">{{ __('pm::client.phone') }}</label>
                        <input
                            class="form-input @error('form.phone') has-error @enderror"
                            id="phone"
                            name="phone"
                            type="text"
                            wire:model="form.phone"
                        />
                        @error('form.phone')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- <div class="form-box">
                        <label for="services">{{ __('pm::client.services') }}</label>
                        <x-synapse-adv-select
                            wire-model="form.services"
                            :options="$services"
                            :multiple="true"
                            placeholder="{{ __('pm::client.select_services') }}"
                        />
                        @error('form.services')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div> --}}

                    <div class="form-box">
                        <label for="social_media">{{ __('pm::client.social_media') }}</label>
                        <textarea
                            class="form-input @error('form.social_media') has-error @enderror w-full"
                            id="social_media"
                            name="social_media"
                            rows="4"
                            placeholder="{{ __('pm::client.social_media_placeholder') }}"
                            wire:model="form.social_media"
                        ></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('pm::client.social_media_help') }}
                        </p>
                        @error('form.social_media')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label
                            class="form-label"
                            for="npwp_image"
                        >{{ __('pm::client.npwp_image') }}</label>
                        <input
                            class="form-input"
                            id="npwp_image"
                            name="npwp_image"
                            type="file"
                            wire:model.defer="form.npwp_image"
                            accept=".png,.jpg,.jpeg,.webp"
                        />
                        @error('npwp_image')
                            <p class="form-error-message">
                                {{ $message }}
                            </p>
                        @enderror
                        <div
                            class="mt-1 text-sm text-blue-600 dark:text-blue-400"
                            wire:loading
                            wire:target="form.npwp_image"
                        >
                            <span class="fa-solid fa-spinner fa-spin mr-1"></span>
                            Uploading file...
                        </div>
                    </div>

                    <div class="form-box required">
                        <label for="status">{{ __('pm::client.status') }}</label>
                        <x-synapse-select
                            id="status"
                            name="status"
                            :options="[
                                0 => __('pm::client.prospect'),
                                1 => __('pm::client.active_client'),
                            ]"
                            wire:model="form.status"
                        />
                        @error('form.status')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="active">{{ __('pm::client.active') }}</label>
                        <x-synapse-toggler wire:model="form.active" />
                        @error('form.active')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: User Data -->
        <div class="space-y-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('pm::client.user_account') }}</h3>
                </div>
                <div class="card-body">
                    <div class="form-box required">
                        <label for="name">{{ __('pm::client.name') }}</label>
                        <input
                            class="form-input @error('form.name') has-error @enderror"
                            id="name"
                            name="name"
                            type="text"
                            wire:model="form.name"
                            readonly
                        />
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ __('pm::client.name_help') }}
                        </p>
                        @error('form.name')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="username">{{ __('pm::client.username') }}</label>
                        <input
                            class="form-input @error('form.username') has-error @enderror"
                            id="username"
                            name="username"
                            type="text"
                            wire:model="form.username"
                        />
                        @error('form.username')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box required">
                        <label for="email">{{ __('pm::client.email') }}</label>
                        <div class="input-group">
                            <span class="input-group-item left">
                                <span class="fa-solid fa-envelope"></span>
                            </span>
                            <input
                                class="form-input pl-[62px]! @error('form.email') has-error @enderror"
                                id="email"
                                name="email"
                                type="email"
                                wire:model="form.email"
                            />
                        </div>
                        @error('form.email')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box {{ $form->client ? '' : 'required' }}">
                        <label for="password">{{ __('pm::client.password') }}</label>
                        <x-synapse-password
                            id="password"
                            name="password"
                            wire:model="form.password"
                            @class(['has-error' => $errors->has('form.password')])
                        />
                        @if ($form->client)
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ __('pm::client.password_edit_help') }}
                            </p>
                        @endif
                        @error('form.password')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="password_confirmation">{{ __('pm::client.password_confirmation') }}</label>
                        <x-synapse-password
                            id="password_confirmation"
                            name="password_confirmation"
                            wire:model="form.password_confirmation"
                            @class(['has-error' => $errors->has('form.password_confirmation')])
                        />
                        @error('form.password_confirmation')
                            <p class="form-error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-box">
                        <label for="">Password Generator</label>
                        <x-synapse-password-generator
                            :length="12"
                            :model="['form.password', 'form.password_confirmation']"
                        />
                    </div>

                    @if ($form->status === 0)
                        <div class="rounded-md bg-yellow-50 p-4 dark:bg-yellow-900/20">
                            <div class="flex">
                                <div class="shrink-0">
                                    <span class="fa-solid fa-exclamation-triangle text-yellow-400"></span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                        <strong>{{ __('pm::client.prospect_note_strong') }}</strong>
                                        {{ __('pm::client.prospect_note') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
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
        >
            <span class="fa-solid fa-floppy-disk"></span>
            <span
                wire:loading.remove
                wire:target="save"
            >{{ __('pm::client.save') }}</span>
            <span
                wire:loading
                wire:target="save"
            >{{ __('pm::client.saving') }}</span>
        </button>
        <a
            class="btn secondary"
            href="{{ backend_route('pm.client.index') }}"
            wire:navigate
        >
            <span class="fa-solid fa-times"></span> {{ __('pm::client.cancel') }}
        </a>
    </div>
</div>
