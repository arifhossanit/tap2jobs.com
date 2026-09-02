@php
    $blogSidebarLeftAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_LEFT, \App\Models\Ad::PAGE_BLOG);
    $blogSidebarRightAds = getActiveAdsByPosition(\App\Models\Ad::POSITION_REGISTER_RIGHT, \App\Models\Ad::PAGE_BLOG);
    $blogSidebarAds = $blogSidebarAds ?? $blogSidebarLeftAds->merge($blogSidebarRightAds);
@endphp

<div class="col-lg-4">
    <div class="Categories br-10 px-20 bg-light mb-40">
        <h5 class="fs-18 text-secondary mb-4">{{ __('web.post_menu.categories') }}</h5>
        @foreach ($blogCategories as $blogCategory)
            @if ($blogCategory->post_assign_categories_count > 0)
                <p>
                    <a class="fs-14 text-gray" href="{{ route('front.blog.category', $blogCategory->id) }}">
                        {{ $blogCategory->post_assign_categories_count > 0 ? html_entity_decode($blogCategory->name) : '' }}
                        {{ $blogCategory->post_assign_categories_count > 0 ? '( ' . $blogCategory->post_assign_categories_count . ' )' : '' }}
                    </a>
                </p>
            @endif
        @endforeach
    </div>
    <div class="recent-post-section br-10 px-20 bg-light">
        <h5 class="fs-18 text-secondary mb-4">{{ __('web.web_blog.recent_posts') }}</h5>
        @foreach ($popularBlogs as $popularBlog)
            <div class="recent-post d-flex mb-40">
                <div class="img">
                    <a href="{{ route('front.posts.details', $popularBlog->id) }}">
                        <img src="{{ !empty($popularBlog->blog_image_url) ? $popularBlog->blog_image_url : asset('assets/img/infyom-logo.png') }}"
                            class="recent-post-img">
                    </a>
                </div>
                <a href="{{ route('front.posts.details', $popularBlog->id) }}" class="fs-14 text-secondary">
                    <div class="desc {{ getFrontSelectLanguage() == 'ar' ? 'me-4' : 'ms-4' }}">
                        <p class="fs-14 text-secondary mb-0">
                            {{ html_entity_decode($popularBlog->title) }}
                        </p>
                        <span
                            class="fs-14 text-gray">{{ \Carbon\Carbon::parse($popularBlog->created_at)->translatedFormat('M jS Y') }}</span>
                    </div>
                </a>
            </div>
        @endforeach
    </div>
    @if ($blogSidebarAds->isNotEmpty())
        <div class="blog-sidebar-ads mt-40">
            @include('front_web.common.register_side_ad', ['ads' => $blogSidebarAds])
        </div>
    @endif
</div>
