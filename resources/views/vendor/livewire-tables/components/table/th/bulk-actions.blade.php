@aware(['component', 'tableName'])
@php
    $customAttributes = $component->getBulkActionsThAttributes();
    $bulkActionsThCheckboxAttributes = $component->getBulkActionsThCheckboxAttributes();
    $theme = $component->getTheme();
@endphp

@if ($component->bulkActionsAreEnabled() && $component->hasBulkActions())
    <x-livewire-tables::table.th.plain wire:key="{{ $tableName }}-thead-bulk-actions" :displayMinimisedOnReorder="true" :$customAttributes>
        <div
            x-data="{newSelectCount: 0, indeterminateCheckbox: false, bulkActionHeaderChecked: false}"
            x-init="$watch('selectedItems', value => indeterminateCheckbox = (value.length > 0 && value.length < paginationTotalItemCount))"
            x-cloak x-show="currentlyReorderingStatus !== true"
            @class([
                'inline-flex rounded-md shadow-sm' => $theme === 'tailwind',
                'form-check d-inline-flex align-items-center dropdown' => $theme === 'bootstrap-5' || $theme === 'bootstrap-4',
            ])
        >
            <input
                x-init="$watch('indeterminateCheckbox', value => $el.indeterminate = value); $watch('selectedItems', value => newSelectCount = value.length);"
                x-on:click="if(selectedItems.length > 0) { $el.indeterminate = false; clearSelected(); bulkActionHeaderChecked = false; } else { bulkActionHeaderChecked = true; $el.indeterminate = false; selectAllOnPage(); }"
                type="checkbox"
                :checked="selectedItems.length > 0"
                {{
                    $attributes->merge($bulkActionsThCheckboxAttributes)->class([
                        'cursor-pointer rounded border-gray-300 text-indigo-600 shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600' => ($theme === 'tailwind') && ($bulkActionsThCheckboxAttributes['default'] ?? true),
                        'form-check-input cursor-pointer me-1' => ($theme === 'bootstrap-5') && ($bulkActionsThCheckboxAttributes['default'] ?? true),
                        'except' => 'default',
                    ])
                }}
            />
            <button type="button" class="btn btn-sm p-0 border-0 shadow-none bg-transparent ms-1" data-bs-toggle="dropdown" data-toggle="dropdown" aria-expanded="false" style="line-height:1; color: #3f4254 !important;">
                <i class="fas fa-chevron-down" style="color: #3f4254 !important; font-size: 11px;"></i>
            </button>
            <ul class="dropdown-menu shadow-sm py-1" style="font-size: 13px; min-width: 180px;">
                <li>
                    <a class="dropdown-item py-1.5 px-3" href="javascript:void(0)" x-on:click="selectAllOnPage()">
                        <i class="fa fa-file-text-o me-2 text-primary"></i>@lang('Select All') (@lang('This Page'))
                    </a>
                </li>
                <li>
                    <a class="dropdown-item py-1.5 px-3" href="javascript:void(0)" x-on:click="setAllSelected()">
                        <i class="fa fa-check-square-o me-2 text-success"></i>@lang('Select All') (@lang('All Pages'))
                    </a>
                </li>
                <li><hr class="dropdown-divider my-1"></li>
                <li>
                    <a class="dropdown-item py-1.5 px-3 text-danger" href="javascript:void(0)" x-on:click="clearSelected()">
                        <i class="fa fa-times me-2"></i>@lang('Deselect All')
                    </a>
                </li>
            </ul>
        </div>
    </x-livewire-tables::table.th.plain>
@endif
