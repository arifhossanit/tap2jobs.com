<div class="ms-auto">
    <div class="dropdown d-flex align-items-center">
        <button class="btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0"
                type="button" id="jobApplicationFilterBtn" data-bs-toggle="dropdown"
                aria-expanded="false" data-bs-auto-close="outside">
            <i class="fas fa-filter"></i>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="jobApplicationFilterBtn">
            <div class="text-start border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <label for="jobApplicationListFilter" class="form-label">
                        {{ __('messages.common.application_list') }}:
                    </label>
                    <select id="jobApplicationListFilter" class="form-select"
                            wire:change="changeApplicationListFilter($event.target.value)">
                        <option value="active" @selected($component->applicationList === 'active')>{{ __('messages.common.active') }}</option>
                        <option value="archived" @selected($component->applicationList === 'archived')>{{ __('messages.common.archived') }}</option>
                        <option value="all" @selected($component->applicationList === 'all')>{{ __('messages.common.all') }}</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="jobApplicationStatusFilter" class="form-label">
                        {{ __('messages.common.status') }}:
                    </label>
                    <select id="jobApplicationStatusFilter" class="form-select"
                            wire:change="changeApplicationStatusFilter($event.target.value)">
                        <option value="">{{ __('messages.filter_name.select_status') }}</option>
                        <option value="1" @selected($component->statusFilter === '1')>{{ __('messages.common.applied') }}</option>
                        <option value="2" @selected($component->statusFilter === '2')>{{ __('messages.common.declined') }}</option>
                        <option value="3" @selected($component->statusFilter === '3')>{{ __('messages.common.hired') }}</option>
                        <option value="4" @selected($component->statusFilter === '4')>{{ __('messages.common.ongoing') }}</option>
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" wire:click="resetApplicationFilters">
                        {{ __('messages.common.reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
