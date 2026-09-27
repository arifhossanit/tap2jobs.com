<?php

return [
    'permissions' => [
        'admin.dashboard',
        'admin.manage_admins',
        'admin.manage_roles',
        'admin.manage_employers',
        'admin.manage_candidates',
        'admin.manage_jobs',
        'admin.manage_references',
        'admin.manage_consultations',
        'admin.manage_content',
        'admin.send_bulk_email',
        'admin.manage_media',
        'admin.manage_settings',
    ],

    // A regular Admin receives operational access. Sensitive user and system
    // administration remains exclusive to the Super Admin by default.
    'admin_permissions' => [
        'admin.dashboard',
        'admin.manage_employers',
        'admin.manage_candidates',
        'admin.manage_jobs',
        'admin.manage_references',
        'admin.manage_consultations',
        'admin.manage_content',
        'admin.send_bulk_email',
        'admin.manage_media',
        'admin.manage_billing',
    ],

    'route_groups' => [
        'admin.dashboard' => [
            'dashboard*', 'dashboard-chart-data*', 'profile', 'profile-update', 'change-password',
        ],
        'admin.manage_admins' => ['admins*'],
        'admin.manage_roles' => ['roles-permissions*'],
        'admin.manage_employers' => ['employers*', 'reported-employers*'],
        'admin.manage_candidates' => [
            'candidates*', 'candidates-export-excel*', 'resumes*', 'delete-resumes*',
            'reported-candidates*', 'selected-candidates*',
        ],
        'admin.manage_jobs' => [
            'jobs*', 'government-jobs*', 'pending-jobs*', 'pending-jobs-add-reason*',
            'pending-jobs-change-status*', 'reported-jobs*', 'expired-jobs*',
            'expire-in-7-days*', 'job-notifications*', 'employer-jobs*',
        ],
        'admin.manage_consultations' => ['consultation-leads*'],
        'admin.manage_content' => [
            'subscribers*', 'cms-services*', 'cms-about-us*', 'faqs*', 'faq-categories*',
            'inquires*', 'privacy-policy*', 'terms-conditions*', 'noticeboards*',
            'post-categories*', 'posts*', 'post-comments*',
        ],
        'admin.send_bulk_email' => ['bulk-email*'],
        'admin.manage_media' => ['ads*', 'media*'],
        'admin.manage_billing' => [
            'plans*', 'transactions*', 'invoices*', 'change-transaction-status*',
        ],
        'admin.manage_settings' => [
            'settings*', 'front-settings*', 'notification-settings*', 'email-template*', 'logs*',
        ],
        'admin.manage_references' => [
            'profile-references*', 'job-categories*', 'job-types*', 'job-tags*', 'job-shifts*',
            'job-workplaces*', 'job-employment-statuses*', 'job-experience-units*',
            'job-gender-preferences*', 'company-sizes*', 'company-categories*', 'skills*',
            'skill-learning-sources*', 'industries*', 'functional-areas*', 'career-levels*',
            'salary-currencies*', 'salary-periods*', 'ownership-types*', 'degree-levels*',
            'education-degree-titles*', 'education-major-groups*', 'education-boards*',
            'education-results*', 'countries*', 'states*', 'divisions*', 'cities*',
            'districts*', 'city-villages*', 'thanas*', 'genders*', 'languages*',
            'language-proficiencies*', 'online-profile-platforms*', 'marital-status*',
            'candidate-religions*', 'blood-groups*', 'disability-difficulties*',
            'candidate-reference-relations*', 'employer-reference-relations*',
            'employer-disability-facilities*', 'army-ba-no-prefixes*', 'army-ranks*',
            'army-employment-types*', 'army-arms*', 'consultation-types*',
            'consultation-contact-methods*',
        ],
    ],
];
