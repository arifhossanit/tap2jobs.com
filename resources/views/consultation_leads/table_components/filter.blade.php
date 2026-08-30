<div class="ms-auto" wire:ignore>
    <div class="dropdown d-flex align-items-center {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} me-md-2">
        <button class="btn btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
                id="consultationLeadFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            <p class="text-center">
                <i class="fas fa-filter"></i>
            </p>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="consultationLeadFilterBtn">
            <div class="text-start border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <label for="consultationLeadStatusFilter" class="form-label">{{ __('messages.common.status') }}:</label>
                    <select id="consultationLeadStatusFilter" class="form-select io-select2" data-control="select2">
                        <option value="">All Status</option>
                        @foreach (\App\Models\ConsultationLead::STATUSES as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="consultationLeadCategoryFilter" class="form-label">Category:</label>
                    <select id="consultationLeadCategoryFilter" class="form-select io-select2" data-control="select2">
                        <option value="">All Categories</option>
                        @foreach (\App\Models\CompanyCategory::query()->orderBy('sort_order')->orderBy('name')->pluck('name', 'id') as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-secondary" id="consultationLeadFilterReset">
                        {{ __('messages.common.reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
