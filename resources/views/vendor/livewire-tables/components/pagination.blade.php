@aware(['component'])
@props(['rows'])

@if ($component->hasConfigurableAreaFor('before-pagination'))
    @include(
        $component->getConfigurableAreaFor('before-pagination'),
        $component->getParametersForConfigurableArea('before-pagination'))
@endif

@if ($component->isTailwind())
    <div>
        @if ($component->paginationVisibilityIsEnabled())
            <div class="mt-4 px-4 md:p-0 sm:flex justify-between items-center space-y-4 sm:space-y-0">
                <div>
                    @if ($component->paginationIsEnabled() && $component->isPaginationMethod('standard') && $rows->lastPage() > 1)
                        <p class="paged-pagination-results text-sm text-gray-700 leading-5 dark:text-white">
                            @if ($component->showPaginationDetails())
                                <span>@lang('Showing')</span>
                                <span class="font-medium">{{ $rows->firstItem() }}</span>
                                <span>@lang('to')</span>
                                <span class="font-medium">{{ $rows->lastItem() }}</span>
                                <span>@lang('of')</span>
                                <span class="font-medium"><span x-text="paginationTotalItemCount"></span></span>
                                <span>@lang('results')</span>
                            @endif
                        </p>
                    @elseif ($component->paginationIsEnabled() && $component->isPaginationMethod('simple'))
                        <p class="paged-pagination-results text-sm text-gray-700 leading-5 dark:text-white">
                            @if ($component->showPaginationDetails())
                                <span>@lang('Showing')</span>
                                <span class="font-medium">{{ $rows->firstItem() }}</span>
                                <span>@lang('to')</span>
                                <span class="font-medium">{{ $rows->lastItem() }}</span>
                            @endif
                        </p>
                    @elseif ($component->paginationIsEnabled() && $component->isPaginationMethod('cursor'))
                    @else
                        <p class="total-pagination-results text-sm text-gray-700 leading-5 dark:text-white">
                            @lang('Showing')
                            <span class="font-medium">{{ $rows->count() }}</span>
                            @lang('results')
                        </p>
                    @endif
                </div>

                @if ($component->paginationIsEnabled())
                    {{ $rows->links('livewire-tables::specific.tailwind.' . (!$component->isPaginationMethod('standard') ? 'simple-' : '') . 'pagination') }}
                @endif
            </div>
        @endif
    </div>
@elseif ($component->isBootstrap4())
    <div>
        @if ($component->paginationVisibilityIsEnabled())
            @if ($component->paginationIsEnabled() && $component->isPaginationMethod('standard') && $rows->lastPage() > 1)
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $rows->links('livewire-tables::specific.bootstrap-4.pagination') }}
                    </div>

                    <div class="col-12 col-md-6 text-center text-md-right text-muted">
                        @if ($component->showPaginationDetails())
                            <span>@lang('Showing')</span>
                            <strong>{{ $rows->count() ? $rows->firstItem() : 0 }}</strong>
                            <span>@lang('to')</span>
                            <strong>{{ $rows->count() ? $rows->lastItem() : 0 }}</strong>
                            <span>@lang('of')</span>
                            <strong><span x-text="paginationTotalItemCount"></span></strong>
                            <span>@lang('results')</span>
                        @endif
                    </div>
                </div>
            @elseif ($component->paginationIsEnabled() && $component->isPaginationMethod('simple'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $rows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>

                    <div class="col-12 col-md-6 text-center text-md-right text-muted">
                        @if ($component->showPaginationDetails())
                            <span>@lang('Showing')</span>
                            <strong>{{ $rows->count() ? $rows->firstItem() : 0 }}</strong>
                            <span>@lang('to')</span>
                            <strong>{{ $rows->count() ? $rows->lastItem() : 0 }}</strong>
                        @endif
                    </div>
                </div>
            @elseif ($component->paginationIsEnabled() && $component->isPaginationMethod('cursor'))
                <div class="row mt-3">
                    <div class="col-12 col-md-6 overflow-auto">
                        {{ $rows->links('livewire-tables::specific.bootstrap-4.simple-pagination') }}
                    </div>
                </div>
            @else
                <div class="row mt-3">
                    <div class="col-12 text-muted">
                        @lang('Showing')
                        <strong>{{ $rows->count() }}</strong>
                        @lang('results')
                    </div>
                </div>
            @endif
        @endif
    </div>
@elseif ($component->isBootstrap5())
    <div>
        @if ($component->paginationVisibilityIsEnabled())
            @if ($component->paginationIsEnabled() && $component->perPageVisibilityIsEnabled())
                @if ($component->paginationIsEnabled() && $rows->lastPage() > 1)
                    <div class="d-flex align-items-center justify-content-between flex-wrap mb-5 mt-3 gap-3">
                        <div class="d-flex align-items-center flex-wrap gap-3">
                            <div class="d-flex align-items-center">
                                <span class="{{ checkLanguageSession() == 'ar' ? 'ms-3' : 'me-3' }} text-gray-600 fs-4 fs-xl-6">@lang('Show')</span>
                                <select wire:model.live="perPage" id="perPage"
                                    class="form-select w-auto data-sorting pl-1 pr-5 py-2 border-0">
                                    @foreach ($component->getPerPageAccepted() as $item)
                                        <option value="{{ $item }}"
                                            wire:key="per-page-{{ $item }}-{{ $component->getTableName() }}">
                                            {{ $item === -1 ? __('All') : $item }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="text-gray-600 fs-4 fs-xl-6">
                                <span>@lang('Showing')</span>
                                <span class="fw-bold">{{ $rows->count() ? $rows->firstItem() : 0 }}</span>
                                <span>@lang('to')</span>
                                <span class="fw-bold">{{ $rows->lastItem() }}</span>
                                <span>@lang('of')</span>
                                <span class="fw-bold">{{ $rows->total() }}</span>
                                <span>@lang('results')</span>
                            </div>
                        </div>
                        <div class="overflow-auto pagination-center ms-auto">
                            {{ $rows->links('livewire-tables::specific.bootstrap-4.pagination') }}
                        </div>
                    </div>
                @else
                    <div class="d-flex align-items-center flex-row flex-wrap mb-5 mt-3 gap-3">
                        <div class="d-flex align-items-center">
                            <span class="{{ checkLanguageSession() == 'ar' ? 'ms-3' : 'me-3' }} text-gray-600 fs-4 fs-xl-6">@lang('Show')</span>
                            <select wire:model.live="perPage" id="perPage"
                                class="form-select w-auto data-sorting pl-1 pr-5 py-2 border-0">
                                @foreach ($component->getPerPageAccepted() as $item)
                                    <option value="{{ $item }}"
                                        wire:key="per-page-{{ $item }}-{{ $component->getTableName() }}">
                                        {{ $item === -1 ? __('All') : $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-gray-600 fs-4">
                            @lang('Showing')
                            <strong>{{ $rows->count() }}</strong>
                            @lang('results')
                        </div>
                    </div>
                @endif
            @endif
        @endif
    </div>
@endif

@if ($component->hasConfigurableAreaFor('after-pagination'))
    @include(
        $component->getConfigurableAreaFor('after-pagination'),
        $component->getParametersForConfigurableArea('after-pagination'))
@endif
