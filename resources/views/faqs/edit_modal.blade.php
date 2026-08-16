<div id="editFAQsModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.faq.edit_faq') }}</h3>
                <button type="button" aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            {{ Form::open(['id'=>'editFAQsForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger fs-4 text-white d-flex align-items-center  d-none"
                     id="editValidationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                {{ Form::hidden('faqId',null,['id'=>'faqId']) }}
                <div class="mb-5">
                    {{ Form::label('faq_category_id', 'Category:', ['class' => 'form-label']) }}
                    {{ Form::select('faq_category_id', $faqCategories ?? [], null, ['id' => 'editFaqCategoryId', 'class' => 'form-select', 'placeholder' => 'Select category']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('title_en', __('messages.faq.title').' (English):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('title_en', null, ['id' => 'editFaqTitleEn', 'class' => 'form-control', 'required', 'placeholder' => 'Enter English question']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('title_bn', __('messages.faq.title').' (বাংলা):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::text('title_bn', null, ['id' => 'editFaqTitleBn', 'class' => 'form-control', 'required', 'placeholder' => 'বাংলা প্রশ্ন লিখুন']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('description_en', __('messages.faq.description').' (English):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    <div id="editFaqDescriptionEnQuillData"></div>
                    {{ Form::hidden('description_en', null, ['id' => 'edit_faqs_desc_en']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('description_bn', __('messages.faq.description').' (বাংলা):', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    <div id="editFaqDescriptionBnQuillData"></div>
                    {{ Form::hidden('description_bn', null, ['id' => 'edit_faqs_desc_bn']) }}
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit','class' => 'btn btn-primary m-0','id' => 'editFaqSaveBtn','data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                <button type="button" class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                        id="btnEditCancel"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
