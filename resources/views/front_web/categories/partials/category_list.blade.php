@if ($jobCategories->count() > 0)
    <div class="row categories-grid">
        @foreach ($jobCategories as $jobCategory)
            <div class="col-xl-4 col-md-6 categories-grid__item">
                <div class="card category-card">
                    <div class="category-card__header">
                        <div class="category-card__image-wrap {{ getFrontSelectLanguage() == 'ar' ? 'ms-4' : 'me-4' }}">
                            <img src="{{ $jobCategory->image_url }}" class="category-card__image"
                                alt="{{ html_entity_decode($jobCategory->name) }}">
                        </div>
                        <div class="category-card__content">
                            <a href="{{ route('front.search.jobs', ['categories' => $jobCategory->id]) }}"
                                class="text-secondary primary-link-hover">
                                <h5 class="category-card__title">{{ html_entity_decode($jobCategory->name) }}</h5>
                            </a>
                            <p class="category-card__count">
                                <i class="fa-solid fa-briefcase"></i>
                                <span>{{ ($jobCategory->jobs_count ? $jobCategory->jobs_count : 0) . ' ' . __('web.open_positions') }}</span>
                            </p>
                        </div>
                        @if ($jobCategory->is_featured)
                            <div class="category-card__featured">
                                <i class="text-primary fa-solid fa-bookmark"></i>
                            </div>
                        @endif
                    </div>

                    <div class="category-card__footer">
                        @if ($jobCategory->jobs_count <= 0)
                            <span class="jobs-position category-card__action is-disabled">
                                <i class="fa-regular fa-circle-xmark"></i>
                                {{ __('web.no_positions') }}
                            </span>
                        @else
                            <a href="{{ route('front.search.jobs', ['categories' => $jobCategory->id]) }}"
                                class="jobs-position category-card__action">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                {{ __('web.open_positions') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @include('front_web.categories.partials.pagination', ['paginator' => $jobCategories])
@else
    <div class="categories-empty text-center py-5">
        <h5 class="fs-18 text-secondary mb-2">{{ __('messages.common.no_data_available') }}</h5>
    </div>
@endif
