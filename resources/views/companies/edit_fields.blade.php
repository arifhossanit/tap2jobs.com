<div class="row">
    {{ Form::hidden('user_id',$user->id) }}
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('name', __('messages.company.company_name').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('name', old('name', isset($user)?$user->full_name:null), ['class' => 'form-control','required', 'placeholder' => __('messages.company.company_name')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('company_name_bn', __('messages.company.company_name_bn').':', ['class' => 'form-label']) }}
        {{ Form::text('company_name_bn', old('company_name_bn', isset($company) ? $company->company_name_bn : null), ['class' => 'form-control', 'placeholder' => __('messages.company.company_name_bn')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('email', __('messages.company.email').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::email('email', old('email', isset($user)?$user->email:null), ['class' => 'form-control', 'required', 'placeholder' => __('messages.company.email')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5 mobile-itel-width">
        {{ Form::label('phone', __('messages.user.phone').':', ['class' => 'form-label']) }}
        <br>
        {{ Form::tel('phone', old('phone', isset($user) ? $user->phone : null), ['class' => 'form-control', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','id'=>'phoneNumber','placeholder' => __('messages.inquiry.phone_no')]) }}
        {{ Form::hidden('region_code',old('region_code', isset($user) ? $user->region_code : null),['id'=>'prefix_code']) }}
        <p id="valid-msg" class="text-success d-none fw-400 fs-small mt-2">{{__('messages.phone.valid_number')}}</p>
        <p id="error-msg" class="text-danger d-none fw-400 fs-small mt-2"></p>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('contact_person_name', __('messages.company.contact_person_name').':', ['class' => 'form-label']) }}
        {{ Form::text('contact_person_name', old('contact_person_name', isset($company) ? ($company->contact_person_name ?: $user->full_name) : null), ['class' => 'form-control', 'placeholder' => __('messages.company.contact_person_name')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('ceo', __('messages.company.contact_person_designation').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('ceo', old('ceo', (isset($company) ? $company->ceo: null)), ['class' => 'form-control','required', 'placeholder' => __('messages.company.contact_person_designation')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('industry_id', __('messages.company.industry').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('industry_id', $data['industries'], old('industry_id', isset($company)?$company->industry_id:null), ['id'=>'industryId','class' => 'form-select ','placeholder' => __('messages.company.select_industry'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerIndustryModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('ownership_type_id', __('messages.company.ownership_type').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('ownership_type_id', $data['ownerShipTypes'], old('ownership_type_id', isset($company)?$company->ownership_type_id:null), ['id'=>'ownershipTypeId','class' => 'form-select ','placeholder' => __('messages.company.select_ownership_type'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerOwnerShipTypeModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('country', __('messages.company.country').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap select2-width-input-grp">
            {{ Form::select('country_id', $data['countries'], old('country_id', isset($user)?$user->country_id:null), ['id'=>'countryId','class' => 'form-select ','placeholder' => __('messages.company.select_country'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCountryModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('state', __('messages.company.state').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap select2-width-input-grp">
            {{ Form::select('state_id', (isset($state) && $state!=null)?$state:[], old('state_id', isset($user)?$user->state_id:null), ['id'=>'stateId','class' => 'form-select ','placeholder' => __('messages.company.select_state'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerStateModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('city', __('messages.company.city').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('city_id', (isset($cities) && $cities!=null) ?$cities:[], old('city_id', isset($user)?$user->city_id:null), ['id'=>'cityId','class' => 'form-select ','placeholder' => __('messages.company.select_city'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCityModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('thana', __('messages.thana.thana_name').':', ['class' => 'form-label']) }}
        {{ Form::select('thana_id', (isset($thanas) && $thanas!=null) ? $thanas : [], old('thana_id', isset($user)?$user->thana_id:null), ['id'=>'thanaId','class' => 'form-select ','placeholder' => __('messages.company.select_thana')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('company_size_id', __('messages.company.company_size').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('company_size_id', $data['companySize'], old('company_size_id', isset($company)?$company->company_size_id:null), ['id'=>'companySizeId','class' => 'form-select ','placeholder' => __('messages.company.select_employer_size'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCompanySizeModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('established_in', __('messages.company.established_year').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::selectYear('established_in', date('Y'), 2000, old('established_in', (isset($company->established_in)) ? $company->established_in : ''), ['class' => 'form-select ', 'id' => 'establishedIn','placeholder'=> __('messages.company.select_established_year')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('location', __('messages.company.address').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('location', old('location', (isset($company) ? $company->location: null)), ['class' => 'form-control', 'required', 'placeholder' =>  __('messages.company.address')]) }}
    </div>
    <div class="col-xl-12 col-md-12 col-sm-12 mb-5">
        {{ Form::label('details', __('messages.company.employer_details').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{--        {{ Form::textarea('details', null, ['class' => 'form-control' , 'id' => 'editDetails','rows'=>'5']) }}--}}
        <div id="editAdminEmployerDescriptionQuillData"></div>
        {{ Form::hidden('details', old('details', $company->details), ['id' => 'editAdminEmployerDetail']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('no_of_offices', __('messages.company.no_of_offices').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('no_of_offices', old('no_of_offices', (isset($company) ? $company->no_of_offices: null)), ['class' => 'form-control', 'required', 'placeholder' => __('messages.company.no_of_offices'), 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('website', __('messages.company.website').':', ['class' => 'form-label']) }}
        {{ Form::text('website', old('website', (isset($company) ? $company->website: null)), ['class' => 'form-control', 'placeholder' => __('messages.company.website')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('fax', __('messages.company.fax').':', ['class' => 'form-label']) }}
        {{ Form::text('fax',old('fax', (isset($company) ? $company->fax: null)), ['class' => 'form-control', 'placeholder' => __('messages.company.fax')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('facebook_url', __('messages.company.facebook_url').':', ['class' => 'form-label']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-facebook-f facebook-fa-icon text-primary"></i>
            </div>
            {{ Form::text('facebook_url',old('facebook_url', isset($company->user->facebook_url) ? $company->user->facebook_url : null), ['class' => 'form-control','id'=>'facebookUrl','placeholder'=>'https://www.facebook.com']) }}
        </div>

    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('twitter_url', __('messages.company.twitter_url').':', ['class' => 'form-label']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-twitter twitter-fa-icon text-primary"></i>
            </div>
            {{ Form::text('twitter_url', old('twitter_url', isset($company->user->twitter_url) ? $company->user->twitter_url : null), ['class' => 'form-control','id'=>'twitterUrl','placeholder'=>'https://www.twitter.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('linkedin_url', __('messages.company.linkedin_url').':', ['class' => 'form-label']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-linkedin-in linkedin-fa-icon text-primary"></i>
            </div>
            {{ Form::text('linkedin_url', old('linkedin_url', isset($company->user->linkedin_url) ? $company->user->linkedin_url : null), ['class' => 'form-control','id'=>'linkedInUrl','placeholder'=>'https://www.linkedin.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('google_plus_url', __('messages.company.google_plus_url').':', ['class' => 'form-label']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-google-plus-g google-plus-fa-icon text-danger"></i>
            </div>
            {{ Form::text('google_plus_url', old('google_plus_url', isset($company->user->google_plus_url) ? $company->user->google_plus_url : null), ['class' => 'form-control','id'=>'googlePlusUrl','placeholder'=>'https://www.plus.google.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('pinterest_url', __('messages.company.pinterest_url').':', ['class' => 'form-label']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-pinterest-p pinterest-fa-icon text-danger"></i>
            </div>
            {{ Form::text('pinterest_url', old('pinterest_url', isset($company->user->pinterest_url) ? $company->user->pinterest_url : null), ['class' => 'form-control','id'=>'pinterestUrl','placeholder'=>'https://www.pinterest.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5" io-image-input="true">
        <label for="company_logo" class="form-label">
            {{__('messages.company.company_logo').':'}}
            <span data-bs-toggle="tooltip"
                              data-placement="top"
                              data-bs-original-title="{{  __('messages.setting.image_validation') }}">
        <i class="fas fa-question-circle ml-1  general-question-mark"></i>
</span>
        </label>
        <div class="d-block">
            <div class="image-picker">
                <div class="image previewImage" id="logoPreview"
                     style="background-image: url('{{!empty($company->company_url)) ? $company->company_url : asset('assets/img/infyom-logo.png'}}')">
                </div>
                <span class="picker-edit rounded-circle text-gray-500 fs-small" data-bs-toggle="tooltip"
                      data-placement="top" data-bs-original-title="{{__("messages.tooltip.change_app_logo")}}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        {{ Form::file('image',['class' => 'image-upload d-none', 'accept' => '.png, .jpg, .jpeg']) }}
                    </label>
                </span>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        <label class='form-label '>{{ __('messages.common.status').':' }}</label><br>
        <div class="form-check form-switch mb-3">
            {{ Form::hidden('is_active', 0) }}
            <input class="form-check-input isActive {{ checkLanguageSession() == 'ar' ? 'float-end' : 'float-start' }}" name="is_active" type="checkbox"
                   data-id="{{ $company->id }}"
                   role="switch" id="active" value="1"
                    {{  old('is_active', isset($company)?$company->user->is_active:1) ? 'checked' : '' }}>
        </div>
    </div>
    <!-- Submit Field -->
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-3', 'id' => 'btnSave']) }}
        <a href="{{ route('company.index') }}"
           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
    </div>
</div>
