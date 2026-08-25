<div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('name', __('messages.company.company_name').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('name', old('name'), ['class' => 'form-control','required', 'placeholder' => __('messages.company.company_name')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('company_name_bn', __('messages.company.company_name_bn').':', ['class' => 'form-label']) }}
        {{ Form::text('company_name_bn', old('company_name_bn'), ['class' => 'form-control', 'placeholder' => __('messages.company.company_name_bn')]) }}
    </div>

    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('contact_person_name', __('messages.company.contact_person_name').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('contact_person_name', old('contact_person_name'), ['class' => 'form-control', 'required', 'placeholder' => __('messages.company.contact_person_name')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('phone', __('messages.employer_account.contact_person_mobile').':', ['class' => 'form-label']) }}
        <br>
        {{ Form::tel('phone', old('phone'), ['class' => 'form-control', 'required', 'maxlength' => '11', 'inputmode' => 'numeric', 'pattern' => '[0-9]{1,11}', 'oninput' => 'this.value = this.value.replace(/\D/g,"").slice(0, 11)','id'=>'phoneNumber','placeholder' => __('messages.employer_register.enter_mobile_number')]) }}
        {{ Form::hidden('region_code',old('region_code'),['id'=>'prefix_code']) }}
        <p id="valid-msg" class="text-success d-none fw-400 fs-small mt-2">{{__('messages.phone.valid_number')}}</p>
        <p id="error-msg" class="text-danger d-none fw-400 fs-small mt-2"></p>
    </div>

    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('email', __('messages.employer_account.contact_person_email').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::email('email', old('email'), ['class' => 'form-control', 'required', 'placeholder' => __('messages.employer_register.contact_person_email_placeholder')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('ceo', __('messages.company.contact_person_designation').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('ceo', old('ceo'), ['class' => 'form-control','required', 'placeholder' => __('messages.company.contact_person_designation')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('industry_ids', __('messages.company.industry').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('industry_ids[]', $data['industries'], old('industry_ids', old('industry_id') ? [old('industry_id')] : []), ['id'=>'addEmployerIndustryId','class' => 'form-select','multiple' => true,'required']) }}
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
            {{ Form::select('ownership_type_id', $data['ownerShipTypes'], old('ownership_type_id'), ['id'=>'ownershipTypeId','class' => 'form-select','placeholder' => __('messages.company.select_ownership_type'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerOwnerShipTypeModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('country', __('messages.company.country').':', ['class' => 'form-label ']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('country_id', $data['countries'], old('country_id'), ['id'=>'countryId','class' => 'form-select','placeholder' => __('messages.company.select_country'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCountryModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('state', __('messages.company.state').':', ['class' => 'form-label ']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('state_id', $state ?? [], old('state_id'), ['id'=>'stateId','class' => 'form-select','placeholder' => __('messages.company.select_state'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerStateModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('city', __('messages.company.city').':', ['class' => 'form-label ']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('city_id', $cities ?? [], old('city_id'), ['id'=>'cityId','class' => 'form-select','placeholder' => __('messages.company.select_city'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCityModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('thana', __('messages.thana.thana_name').':', ['class' => 'form-label ']) }}
        {{ Form::select('thana_id', $thanas ?? [], old('thana_id'), ['id'=>'thanaId','class' => 'form-select','placeholder' => __('messages.company.select_thana')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('company_size_id', __('messages.company.company_size').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="input-group flex-nowrap">
            {{ Form::select('company_size_id', $data['companySize'], old('company_size_id'), ['id'=>'companySizeId','class' => 'form-select','placeholder' => __('messages.company.select_employer_size'),'required']) }}
            <div class="input-group-text border-0">
                <a href="javascript:void(0)" class="text-gray-500 createEmployerCompanySizeModal"><i
                            class="fa fa-plus"></i></a>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('established_in', __('messages.company.established_year').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::selectYear('established_in', date('Y'), 2000, old('established_in'), ['class' => 'form-select', 'id' => 'establishedIn','placeholder'=> __('messages.company.select_established_year'),'required']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('location', __('messages.employer_account.company_address').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('location', old('location'), ['class' => 'form-control','required','placeholder' => __('messages.employer_account.company_address')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('company_address_bn', __('messages.employer_account.company_address_bn').':', ['class' => 'form-label']) }}
        {{ Form::text('company_address_bn', old('company_address_bn'), ['class' => 'form-control', 'maxlength' => 1000, 'placeholder' => __('messages.employer_register.company_address_bn_placeholder')]) }}
    </div>

    <div class="col-xl-12 col-md-12 col-sm-12 mb-5">
        {{ Form::label('details', __('messages.company.employer_details').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div id="addAdminEmployerDescriptionQuillData"></div>
        {{ Form::hidden('details', old('details'), ['id' => 'company_desc']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('no_of_offices', __('messages.company.no_of_offices').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('no_of_offices', old('no_of_offices'), ['class' => 'form-control', 'required', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")', 'placeholder' => __('messages.company.no_of_offices')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('website', __('messages.company.website').':', ['class' => 'form-label ']) }}
        {{ Form::text('website', old('website'), ['class' => 'form-control','placeholder' => __('messages.company.website')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('trade_license_no', __('messages.employer_account.trade_license_no').':', ['class' => 'form-label']) }}
        {{ Form::text('trade_license_no', old('trade_license_no'), ['class' => 'form-control', 'maxlength' => 100, 'placeholder' => __('messages.employer_account.enter_trade_license_no')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        <label for="rl_no" class="form-label">
            {{ __('messages.employer_account.rl_no') }} <span class="text-muted fw-normal">({{ __('messages.employer_account.rl_no_only_recruiting_agency') }})</span>
        </label>
        {{ Form::text('rl_no', old('rl_no'), ['class' => 'form-control', 'maxlength' => 100, 'inputmode' => 'numeric', 'pattern' => '[0-9]*', 'oninput' => "this.value = this.value.replace(/\\D/g, '')", 'placeholder' => __('messages.employer_account.enter_number_only')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('fax', __('messages.company.fax').':', ['class' => 'form-label ']) }}
        {{ Form::text('fax',old('fax'), ['class' => 'form-control', 'placeholder' => __('messages.company.fax')]) }}
    </div>

    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('facebook_url', __('messages.company.facebook_url').':', ['class' => 'form-label ']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-facebook-f facebook-fa-icon text-primary"></i>
            </div>
            {{ Form::text('facebook_url',old('facebook_url'), ['class' => 'form-control','id'=>'facebookUrl','placeholder'=>'https://www.facebook.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('twitter_url', __('messages.company.twitter_url').':', ['class' => 'form-label ']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-twitter twitter-fa-icon text-primary"></i>
            </div>
            {{ Form::text('twitter_url', old('twitter_url'), ['class' => 'form-control','id'=>'twitterUrl','placeholder'=>'https://www.twitter.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('linkedin_url', __('messages.company.linkedin_url').':', ['class' => 'form-label ']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-linkedin-in linkedin-fa-icon text-primary"></i>
            </div>
            {{ Form::text('linkedin_url', old('linkedin_url'), ['class' => 'form-control','id'=>'linkedInUrl','placeholder'=>'https://www.linkedin.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('google_plus_url', __('messages.company.google_plus_url').':', ['class' => 'form-label ']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-google-plus-g google-plus-fa-icon text-danger"></i>
            </div>
            {{ Form::text('google_plus_url', old('google_plus_url'), ['class' => 'form-control','id'=>'googlePlusUrl','placeholder'=>'https://www.plus.google.com']) }}
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('pinterest_url', __('messages.company.pinterest_url').':', ['class' => 'form-label ']) }}
        <div class="input-group">
            <div class="input-group-text border-0">
                <i class="fab fa-pinterest-p pinterest-fa-icon text-danger"></i>
            </div>
            {{ Form::text('pinterest_url', old('pinterest_url'), ['class' => 'form-control','id'=>'pinterestUrl','placeholder'=>'https://www.pinterest.com']) }}
        </div>
    </div>
    <div class="col-xl-3 col-md-3 col-sm-12 mb-5" io-image-input="true">
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
                     style="background-image: url({{ asset('assets/img/infyom-logo.png') }})">
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

    <div class="col-xl-3 col-md-3 col-sm-12 mb-5">
        <label class='form-label '>{{ __('messages.common.status').':' }}</label><br>
        <div class="form-check form-switch mb-3">
            {{ Form::hidden('is_active', 0) }}
            <input class="form-check-input isCreateActive {{ checkLanguageSession() == 'ar' ? 'float-end' : 'float-start' }}" name="is_active" type="checkbox"
                   role="switch" id="active" value="1"
                    {{ old('is_active', isset($company)?$company->is_active:1) ? 'checked' : '' }}>
        </div>
    </div>
    @include('companies.partials.disability_fields')
    <div class="col-12 border-top my-4"></div>
    <div class="col-xl-4 col-md-4 col-sm-12 mb-5">
        {{ Form::label('username', __('messages.employer_register.username').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        {{ Form::text('username', old('username'), ['class' => 'form-control', 'required', 'maxlength' => 100, 'placeholder' => __('messages.employer_register.username_placeholder')]) }}
    </div>
    <div class="col-xl-4 col-md-4 col-sm-12 mb-5">
        {{ Form::label('password', __('messages.company.password').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="position-relative">
            <input name="password" type="password" id="password"
                   class="form-control pe-12"
                   {{ (isset($company)) ? '' : 'required' }} placeholder="{{__('messages.company.password')}}">
            <button type="button"
                    class="company-password-toggle position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-gray-600 px-4"
                    data-password-toggle="password" title="Show password" aria-label="Show password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>
    <div class="col-xl-4 col-md-4 col-sm-12 mb-5">
        {{ Form::label('password_confirmation', __('messages.company.confirm_password').':', ['class' => 'form-label']) }}
        <span class="required"></span>
        <div class="position-relative">
            <input name="password_confirmation" type="password" id="confirmPassword"
                   class="form-control pe-12"
                   {{ (isset($company)) ? '' : 'required' }} placeholder="{{__('messages.company.confirm_password')}}">
            <button type="button"
                    class="company-password-toggle position-absolute top-50 end-0 translate-middle-y border-0 bg-transparent text-gray-600 px-4"
                    data-password-toggle="confirmPassword" title="Show password" aria-label="Show password">
                <i class="fas fa-eye"></i>
            </button>
        </div>
    </div>

    <!-- Submit Field -->
    <div class="d-flex justify-content-end">
        {{ Form::submit(__('messages.common.save'),['class' => 'btn btn-primary me-3', 'id' => 'btnSave']) }}
        <a href="{{ route('company.index') }}"
           class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
    </div>
</div>
