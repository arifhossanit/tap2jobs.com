<div id="addAdsModal" tabindex="-1" class="modal fade" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">{{ __('messages.ad.new_ad') }}</h3>
                <button type="button" aria-label="Close" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            {{ Form::open(['id' => 'addAdNewForm', 'files' => true]) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="validationErrorsBox">
                    <i class="fa-solid fa-face-frown me-5"></i>
                </div>
                <div class="row">
                    <div class="col-sm-12 mb-5" io-image-input="true">
                        <label for="ad_image" class="form-label">
                            {{ __('messages.ad.media') . ':' }}
                            <span data-bs-toggle="tooltip" data-placement="top"
                                  data-bs-original-title="{{ __('messages.ad.image_help') }}">
                                <i class="fas fa-question-circle ml-1 general-question-mark"></i>
                            </span>
                        </label>
                        <div class="d-block">
                            <div class="image-picker">
                                <div class="image previewImage d-flex align-items-center justify-content-center text-center"
                                     id="previewImage">
                                    <span class="text-muted fs-12 px-2">{{ __('messages.ad.choose_media') }}</span>
                                </div>
                                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                                      data-placement="top"
                                      data-bs-original-title="{{ __('messages.tooltip.change_image') }}">
                                    <label>
                                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                                        {{ Form::file('ad_image', ['class' => 'image-upload d-none', 'id' => 'addAdImage', 'accept' => '.png, .jpg, .jpeg, .webp, .mp4, .webm, .ogg']) }}
                                    </label>
                                </span>
                            </div>
                            <label for="addAdImage" class="btn btn-light-primary btn-sm mt-3">
                                <i class="fa-solid fa-upload me-2"></i>{{ __('messages.ad.choose_media') }}
                            </label>
                            <small class="text-muted d-block mt-2">{{ __('messages.ad.media_help') }}</small>
                            <div class="ad-upload-progress mt-3 d-none" id="adUploadProgress">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <span class="text-muted fs-12">{{ __('messages.ad.uploading') }}</span>
                                    <span class="text-muted fs-12" id="adUploadProgressText">0%</span>
                                </div>
                                <div class="progress h-6px">
                                    <div class="progress-bar bg-primary" id="adUploadProgressBar" role="progressbar"
                                         style="width: 0%" aria-valuenow="0" aria-valuemin="0"
                                         aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6 mb-5">
                        {{ Form::label('title', __('messages.candidate_profile.title') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title', 'placeholder' => __('messages.candidate_profile.title')]) }}
                    </div>

                    <div class="col-sm-6 mb-5">
                        {{ Form::label('position', __('messages.ad.position') . ':', ['class' => 'form-label']) }}
                        <span class="required"></span>
                        {{ Form::select('position', $positions, null, ['class' => 'form-select', 'id' => 'position', 'required', 'placeholder' => __('messages.ad.select_position')]) }}
                    </div>

                    <div class="col-sm-12 mb-5">
                        {{ Form::label('description', __('messages.ad.description') . ':', ['class' => 'form-label']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'id' => 'description', 'rows' => 3, 'placeholder' => __('messages.ad.description')]) }}
                    </div>

                    <div class="col-sm-6 mb-5">
                        {{ Form::label('link_url', __('messages.ad.link_url') . ':', ['class' => 'form-label']) }}
                        {{ Form::url('link_url', null, ['class' => 'form-control', 'id' => 'linkUrl', 'placeholder' => 'https://']) }}
                    </div>

                    <div class="col-sm-6 mb-5">
                        {{ Form::label('cta_text', __('messages.ad.cta_text') . ':', ['class' => 'form-label']) }}
                        {{ Form::text('cta_text', null, ['class' => 'form-control', 'id' => 'ctaText', 'placeholder' => __('messages.ad.cta_text')]) }}
                    </div>

                    <div class="col-sm-6 mb-5">
                        {{ Form::label('sort_order', __('messages.ad.sort_order') . ':', ['class' => 'form-label']) }}
                        {{ Form::number('sort_order', 0, ['class' => 'form-control', 'id' => 'sortOrder', 'min' => 0]) }}
                    </div>

                    <div class="col-sm-6 mb-5">
                        <label class="form-label">{{ __('messages.common.status') . ':' }}</label>
                        <label class="form-check form-switch form-switch-sm">
                            <input type="checkbox" name="is_active"
                                   class="form-check-input {{ checkLanguageSession() == 'ar' ? 'float-end' : 'float-start' }}"
                                   id="active" checked>
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer pt-0">
                {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary m-0', 'id' => 'adSaveBtn', 'data-loading-text' => "<span class='spinner-border spinner-border-sm'></span> " . __('messages.common.process')]) }}
                <button type="button"
                        class="btn btn-secondary my-0 {{ checkLanguageSession() == 'ar' ? 'me-5' : 'ms-5' }} me-0"
                        data-bs-dismiss="modal">{{ __('messages.common.cancel') }}</button>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
