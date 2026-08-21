@aware(['component', 'tableName'])
@php
    $customAttributes = $component->getBulkActionsThAttributes();
    $bulkActionsThCheckboxAttributes = $component->getBulkActionsThCheckboxAttributes();
    $theme = $component->getTheme();
@endphp

@if ($component->bulkActionsAreEnabled() && $component->hasBulkActions())
    <x-livewire-tables::table.th.plain wire:key="{{ $tableName }}-thead-bulk-actions" :displayMinimisedOnReorder="true" :$customAttributes>
        <div
            x-data="{
                currentPageIds() {
                    return Array.from(this.paginationCurrentItems.values()).map(id => id.toString())
                },
                selectedCurrentPageCount() {
                    const currentPageIds = new Set(this.currentPageIds())

                    return this.selectedItems.filter(id => currentPageIds.has(id.toString())).length
                },
                isCurrentPageSelected() {
                    const currentPageIds = this.currentPageIds()

                    return currentPageIds.length > 0
                        && this.selectedCurrentPageCount() === currentPageIds.length
                },
                deselectCurrentPage() {
                    const currentPageIds = new Set(this.currentPageIds())

                    this.selectedItems = this.selectedItems.filter(id => !currentPageIds.has(id.toString()))
                }
            }"
            x-cloak x-show="currentlyReorderingStatus !== true"
            @class([
                'inline-flex rounded-md shadow-sm' => $theme === 'tailwind',
                'form-check' => $theme === 'bootstrap-5',
            ])
        >
            <input
                x-effect="$el.indeterminate = selectedCurrentPageCount() > 0 && !isCurrentPageSelected()"
                x-on:click="isCurrentPageSelected() ? deselectCurrentPage() : selectAllOnPage()"
                type="checkbox"
                :checked="isCurrentPageSelected()"
                {{
                    $attributes->merge($bulkActionsThCheckboxAttributes)->class([
                        'cursor-pointer rounded border-gray-300 text-indigo-600 shadow-sm transition duration-150 ease-in-out focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-900 dark:text-white dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:bg-gray-600' => ($theme === 'tailwind') && ($bulkActionsThCheckboxAttributes['default'] ?? true),
                        'form-check-input cursor-pointer' => ($theme === 'bootstrap-5') && ($bulkActionsThCheckboxAttributes['default'] ?? true),
                        'except' => 'default',
                    ])
                }}
            />
        </div>
    </x-livewire-tables::table.th.plain>
@endif
