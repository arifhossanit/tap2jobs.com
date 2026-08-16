<div class="ms-auto" wire:ignore>
    <div class="dropdown d-flex align-items-center {{ checkLanguageSession() == 'ar' ? 'ms-4' : 'me-4' }} me-md-2">
        <button class="btn btn-icon btn-primary text-white dropdown-toggle hide-arrow ps-2 pe-0" type="button"
            id="faqFilterBtn" data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
            <p class="text-center mb-0">
                <i class="fas fa-filter"></i>
            </p>
        </button>
        <div class="dropdown-menu py-0" aria-labelledby="faqFilterBtn" style="min-width: 250px;">
            <div class="text-start border-bottom py-4 px-7">
                <h3 class="text-gray-900 mb-0">{{ __('messages.common.filter_options') }}</h3>
            </div>
            <div class="p-5">
                <div class="mb-5">
                    <label for="faqAudienceFilter" class="form-label">Audience:</label>
                    <select id="faqAudienceFilter" class="form-select io-select2" data-control="select2">
                        <option value="">All Audiences</option>
                        <option value="candidate">Candidate</option>
                        <option value="employer">Employer</option>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="faqCategoryFilter" class="form-label">Category:</label>
                    <select id="faqCategoryFilter" class="form-select io-select2" data-control="select2">
                        <option value="">All Categories</option>
                        @if(isset($filterHeads[0]) && (is_array($filterHeads[0]) || $filterHeads[0] instanceof \Countable) && count($filterHeads[0]) > 0)
                            @foreach($filterHeads[0] as $id => $name)
                                @if(!is_array($name))
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="reset" class="btn btn-secondary" id="faq-ResetFilter">
                        {{ __('messages.common.reset') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
