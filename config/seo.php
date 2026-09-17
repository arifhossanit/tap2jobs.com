<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Global defaults
    |--------------------------------------------------------------------------
    |
    | Individual pages can override these values with the Blade sections used
    | by front_web.layouts.app (meta_description, canonical_url, robots, etc.).
    |
    */
    'default_description' => env(
        'SEO_DEFAULT_DESCRIPTION',
        'Find jobs, career opportunities, and trusted employers across Bangladesh with Tap2Jobs.'
    ),

    /* Public workflow and utility pages that should not appear in search. */
    'noindex_routes' => [
        'admin.login',
        'login',
        'password.*',
        'front.user.login',
        'front.candidate.login',
        'front.employee.login',
        'front.register',
        'candidate.register',
        'employer.register',
        'show.apply-job-form',
        'get.jobs.search',
    ],

    /*
     * Functional parameters create transient/filter results. Pagination and
     * campaign parameters are intentionally absent from this list.
     */
    'noindex_query_parameters' => [
        'search',
        'keyword',
        'keywords',
        'categories',
        'category',
        'location',
        'job_type',
        'jobType',
        'company',
        'salary_from',
        'salary_to',
        'experience',
        'jobExperience',
        'jobExperienceFrom',
        'jobExperienceTo',
        'career_level',
        'functional_area',
        'gender',
        'skill',
        'filter',
        'deadline',
        'organization',
        'source',
        'overseas',
        'work_from_home',
        'is_fresher',
        'is_featured',
        'matching',
    ],

    /*
     * These listing routes are faceted-search pages. Their clean URL and a
     * pagination-only URL may be indexed; every other query combination is a
     * transient filter result and must remain crawlable but out of the index.
     */
    'faceted_routes' => [
        'front.search.jobs',
        'front.job-categories.show',
    ],

    /* Query parameters that do not turn a clean listing into a filter page. */
    'indexable_query_parameters' => [
        'page',
    ],

    /* Marketing attribution is stripped from canonical URLs, not noindexed. */
    'tracking_query_parameters' => [
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'gclid',
        'fbclid',
        'msclkid',
    ],

    /* Google Indexing API supports JobPosting URLs, not general web pages. */
    'indexing_api' => [
        'enabled' => env('GOOGLE_INDEXING_ENABLED', false),
        'credentials_path' => env(
            'GOOGLE_INDEXING_CREDENTIALS',
            'storage/app/google-indexing-service-account.json'
        ),
        'endpoint' => 'https://indexing.googleapis.com/v3/urlNotifications:publish',
        'timeout' => 15,
        'queue' => env('GOOGLE_INDEXING_QUEUE', 'default'),
        'batch_size' => env('GOOGLE_INDEXING_BATCH_SIZE', 180),
    ],
];
