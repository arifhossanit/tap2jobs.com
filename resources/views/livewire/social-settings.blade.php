{{ Form::open(['route' => 'settings.update', 'id' => 'editSocialSettingForm']) }}
    {{ Form::hidden('sectionName', $sectionName) }}
    <div class="row mt-3">
        <div class="col-sm-6 mt-5">
            {{ Form::label('facebook_url', __('messages.setting.facebook_url') . ':', ['class' => 'form-label']) }}
            <div class="input-group">
                <div class="input-group-text border-0">
                    <i class="fab fa-facebook-f facebook-fa-icon text-primary"></i>
                </div>
                {{ Form::text('facebook_url', $setting['facebook_url'], ['class' => 'form-control', 'id' => 'facebookUrl', 'placeholder' => __('messages.setting.facebook_url')]) }}
            </div>
        </div>
        <div class="col-sm-6 mt-5">
            {{ Form::label('instagram_url', 'Instagram URL:', ['class' => 'form-label']) }}
            <div class="input-group">
                <div class="input-group-text border-0">
                    <i class="fab fa-instagram instagram-fa-icon text-danger"></i>
                </div>
                {{ Form::text('instagram_url', $setting['instagram_url'] ?? '', ['class' => 'form-control', 'id' => 'instagramUrl', 'placeholder' => 'https://www.instagram.com/username']) }}
            </div>
        </div>
        <div class="col-sm-6 mt-5">
            {{ Form::label('linkedIn_url', __('messages.setting.linkedIn_url') . ':', ['class' => 'form-label']) }}
            <div class="input-group">
                <div class="input-group-text border-0">
                    <i class="fab fa-linkedin-in linkedin-fa-icon text-primary"></i>
                </div>
                {{ Form::text('linkedIn_url', $setting['linkedIn_url'], ['class' => 'form-control', 'id' => 'linkedInUrl', 'placeholder' => __('messages.setting.linkedIn_url')]) }}
            </div>
        </div>
    </div>
    <div class="row mt-4 mb-5">
        <div class="d-flex justify-content-end">
            {{ Form::button(__('messages.common.save'), ['type' => 'submit', 'class' => 'btn btn-primary me-3', 'id' => 'submitId']) }}
            <a href="{{ route('settings.index', ['section' => 'social_settings']) }}"
                class="btn btn-secondary me-2">{{ __('messages.common.cancel') }}</a>
        </div>
    </div>
    {{ Form::close() }}
