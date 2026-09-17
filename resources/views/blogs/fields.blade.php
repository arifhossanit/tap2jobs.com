<div class="row">
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('title',__('messages.post.title').':', ['class' => 'form-label ']) }}<span
                class="required"></span>
        {{ Form::text('title', null, ['class' => 'form-control','required', 'placeholder' => __('messages.post.title')]) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('slug', 'SEO URL Slug:', ['class' => 'form-label']) }}
        {{ Form::text('slug', null, ['class' => 'form-control', 'maxlength' => 180, 'placeholder' => 'Auto-generated from title when empty']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('meta_title', 'SEO Title:', ['class' => 'form-label']) }}
        {{ Form::text('meta_title', null, ['class' => 'form-control', 'maxlength' => 180, 'placeholder' => 'Defaults to article title']) }}
    </div>
    <div class="col-12 mb-5">
        {{ Form::label('meta_description', 'Meta Description:', ['class' => 'form-label']) }}
        {{ Form::textarea('meta_description', null, ['class' => 'form-control', 'rows' => 3, 'maxlength' => 255, 'placeholder' => 'Defaults to a summary of the article']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('blog_category_id', __('messages.post_category.post_category').':', ['class' => 'form-label ']) }}
        <span class="text-danger">*</span>
        {{Form::select('blogCategories[]', $blogCategories, isset($post)?$selectedBlogCategories:null, ['class' => 'form-select','id'=>'blog_category_id','multiple'=>true,'required']) }}
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5" io-image-input="true">
        <label for="category_image" class="form-label">
            {{__('messages.post.image').':'}}
            <span class="required"></span>
           <span data-bs-toggle="tooltip"
                              data-placement="top"
                              data-bs-original-title="{{  __('messages.setting.image_validation') }}">
        <i class="fas fa-question-circle ml-1  general-question-mark"></i>
</span>
        </label>
        <div class="d-block">
            <div class="image-picker">
                <div class="image previewImage" id="previewImage"
                     style="background-image: url({{ !empty($post->blog_image_url) ? asset($post->blog_image_url) : asset('front_web/images/blog-1.png') }})">
                </div>
                <span class="picker-edit rounded-circle text-gray-500 fs-small"
                      data-bs-toggle="tooltip"
                      data-placement="top" data-bs-original-title="{{__('messages.tooltip.change_image')}}">
                    <label>
                        <i class="fa-solid fa-pen" id="profileImageIcon"></i>
                        {{ Form::file('image',['class' => 'image-upload d-none', 'accept' => '.png, .jpg, .jpeg']) }}
                    </label>
                </span>
            </div>
        </div>
    </div>
    <div class="col-xl-6 col-md-6 col-sm-12 mb-5">
        {{ Form::label('description',__('messages.post.description').':', ['class' => 'form-label ']) }}<span
                class="required"></span>
        <x-text-editor id="postDescription" name="description" :value="old('description', $post->description ?? '')"
                       rows="12" required :placeholder="__('messages.post.description')" />
    </div>
</div>
<div class="d-flex mt-5 justify-content-end">
    {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary me-3','name' => 'save', 'id' => 'saveJob']) }}
    <a href="{{ route('posts.index') }}"
       class="btn btn-secondary me-2">{{__('messages.common.cancel')}}</a>
</div>
