<div id="addJobCategoryModal" class="modal fade" role="dialog" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.job_category.new_job_category') }}</h3>
                <button type="button" aria-label="Close" class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            {{ Form::open(['id'=>'addJobCategoryForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="jobCategoryValidationErrorsBox"></div>
                <div class="mb-5">
                    {{ Form::label('name',__('messages.job_category.name').':', ['class' => 'form-label']) }}
                    <span class="required"></span>
                    {{ Form::textarea('name', null, ['class' => 'form-control', 'required', 'id' => 'addJobCategoryName', 'rows' => 3, 'placeholder' => __('messages.job_category.name')]) }}
                </div>
                <div class="mb-5 h-100">
                    {{ Form::label('description',__('messages.job_category.description').':', ['class' =>'form-label']) }}
                    <span class="required"></span>
                    {{--                        {{ Form::textarea('description', null, ['class' => 'form-control','id' => 'jobCategoryDescription', 'rows' => '5']) }}--}}
                    <x-text-editor id="addJobCategoryDescriptionQuillData" />
                    {{ Form::hidden('description', null, ['id' => 'jobCategoryDescriptionValue']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('slug', 'SEO URL Slug:', ['class' => 'form-label']) }}
                    {{ Form::text('slug', null, ['class' => 'form-control', 'id' => 'addJobCategorySlug', 'maxlength' => 180, 'placeholder' => 'accounting-finance', 'autocomplete' => 'off']) }}
                    <div class="form-text">Automatically generated from the category name. You can customize it before saving.</div>
                </div>
                <div class="mb-5">
                    {{ Form::label('seo_title', 'SEO Title:', ['class' => 'form-label']) }}
                    {{ Form::text('seo_title', null, ['class' => 'form-control', 'maxlength' => 180, 'placeholder' => 'Accounting & Finance Jobs in Bangladesh']) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('meta_description', 'Meta Description:', ['class' => 'form-label']) }}
                    {{ Form::textarea('meta_description', null, ['class' => 'form-control', 'rows' => 3, 'maxlength' => 255]) }}
                </div>
                <div class="mb-5">
                    {{ Form::label('search_tags', 'Search Tags:', ['class' => 'form-label']) }}
                    {{ Form::textarea('search_tags', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Bangladesh Jobs, Accounting Jobs, Finance Jobs']) }}
                    <div class="form-text">Separate tags with commas or new lines. Tags are stored as page metadata and are not shown in the category page content.</div>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-12 mb-5" io-image-input="true">
                    <label for="category_image" class="form-label">
                        {{__('messages.common.category_image').':'}}
                        <span data-bs-toggle="tooltip"
                              data-placement="top"
                              data-bs-original-title="{{  __('messages.setting.image_validation') }}">
        <i class="fas fa-question-circle ml-1  general-question-mark"></i>
</span>
                    </label>
                    <div class="d-block">
                        <div class="image-picker">
                            <div class="image previewImage" id="logoPreview"
                                 style="background-image: url({{ asset('front_web/images/job-categories.png') }})">
                            </div>
                            <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                  data-placement="top" data-bs-original-title="{{__('messages.tooltip.change_image')}}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        {{ Form::file('customer_image',['class' => 'image-upload d-none', 'accept' => '.png, .jpg, .jpeg']) }}
                    </label>
                </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary m-0','id'=>'jobCategoryBtnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> ".__('messages.common.process')]) }}
                    <button type="button" id="jobCategoryBtnCancel" class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                            data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
