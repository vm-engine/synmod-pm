<x-synapps::layouts.app>
    <div x-data="{ pageName: 'Pm', isHome: false, drawerOpen: false }">
        @include('synapps::components.layouts.partials.loader')
        @include('synapps::components.layouts.partials.breadcrumbs')

        <!-- Confirmation Dialog -->
        <x-synapse-confirm-dialog />

        @dump(config('database.connections'));
        <div class="space-y-5 sm:space-y-6">
            <div class="dark:bg-white/3 rounded-2xl border border-gray-200 bg-white dark:border-gray-800">
                <div class="flex justify-between">
                    <div class="px-5 py-4 sm:px-6 sm:py-5">
                        <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                            Pm List
                        </h3>
                    </div>
                    <div class="my-auto flex gap-3 px-5">
                        <div class="input-group">
                            <input
                                class="form-input p-2"
                                type="text"
                                placeholder="{{ __('pm::labels.search') }}"
                            >
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-100 dark:border-gray-800">
                    <div class="max-w-full overflow-x-auto">
                        <table class="datatable min-w-full">
                            <thead>
                                <tr>
                                    <th>
                                        <p>{{ __('pm::labels.no') }}</p>
                                    </th>
                                    <th>
                                        <p>{{ __('pm::labels.name') }}</p>
                                    </th>
                                    <th>
                                        <p>{{ __('pm::labels.actions') }}</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="flex items-center">
                                            <p>1</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="flex items-center">
                                            <p>Sample Item</p>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-center">-</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-synapps::layouts.app>
