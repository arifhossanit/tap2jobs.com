# Bulk Email System: Review and Porting Guide

This document records the bulk email implementation in this project so that it
can be reproduced in another Laravel project without having to rediscover the
flow.

## What the system supports

- Send to all verified candidates or all verified employers.
- Filter candidates by calculated profile-completion range.
- Search for and select verified existing users.
- Enter up to 1,000 custom addresses in the UI.
- Import a CSV/TXT file up to 50 MB and stream it without loading the full file
  into memory.
- Schedule a campaign using the application timezone.
- Personalize subject and HTML body with `{{Name}}`, `{{first_name}}`,
  `{{last_name}}`, and `{{email}}`.
- Compose HTML with TinyMCE and select recipients with Tagify.
- Upload editor images and documents up to 10 MB. Local editor images are
  embedded with a CID; linked local documents are also attached.
- Fan recipients out into individual database queue jobs, spaced two seconds
  apart.
- Retry individual mail jobs up to 10 times and specially release the job when
  the SMTP provider reports "too many emails per second".

## Runtime flow

```text
Admin form
  -> BulkEmailController validation
     -> candidate/employer/existing/custom
        -> DispatchBulkEmail
           -> one delayed SendBulkEmail job per recipient
              -> placeholder substitution
              -> BulkEmailMessage
              -> SMTP transport
     -> CSV
        -> ImportBulkEmailCsv
           -> stream, validate and deduplicate into staging table
           -> DispatchImportedBulkEmail (500 rows at a time)
              -> DispatchBulkEmail
                 -> SendBulkEmail per address
```

All bulk-email jobs explicitly use the `database` queue connection. The `jobs`
and `failed_jobs` migrations must exist, and a database queue worker must run
continuously. Delayed jobs and scheduled sends do not work without the worker.

## Source files to port

Core files:

- `app/Http/Controllers/BulkEmailController.php`
- `app/Services/BulkEmailCsvReader.php`
- `app/Jobs/ImportBulkEmailCsv.php`
- `app/Jobs/DispatchImportedBulkEmail.php`
- `app/Jobs/DispatchBulkEmail.php`
- `app/Jobs/SendBulkEmail.php`
- `app/Mail/BulkEmailMessage.php`
- `resources/views/bulk_email/index.blade.php`
- `resources/views/emails/bulk_email.blade.php`
- `database/migrations/2026_09_29_000001_create_bulk_email_import_recipients_table.php`

Integration points:

- Four `bulk-email` routes in `routes/web.php`.
- Bulk Email menu entry in `resources/views/layouts/menu.blade.php`.
- `admin.send_bulk_email` permission and route mapping in
  `config/admin_permissions.php`.
- `@yaireo/tagify` and `tinymce` dependencies in `package.json`.
- TinyMCE copy rule in `webpack.mix.js` and the generated public assets.
- Existing `jobs` and `failed_jobs` migrations.
- Public storage link (`php artisan storage:link`) for editor uploads.
- SMTP settings and a supervised database queue worker in production.

Tests to port and adapt:

- `tests/Feature/BulkEmailCsvReaderTest.php`
- `tests/Feature/BulkEmailCsvJobsTest.php`
- `tests/Unit/BulkEmailInlineImageTest.php`

## Project-specific dependencies to adapt

Do not blindly copy these assumptions:

- `User::role(...)` requires the target project's role implementation and the
  role names `Candidate` and `Employer`.
- `email_verified_at` is used to exclude unverified registered users.
- The candidate relation is `User::candidate`.
- Candidate segmentation calls `CandidateProfileCompletionService::calculate()`.
- Sender details use the project helper `getSettingValue()`.
- Routes currently rely on the admin middleware stack: `auth`, role,
  `admin.permission`, `xss`, and `verified.user`.
- The view extends this project's admin layout and uses its notification helper.

For a different domain model, isolate audience selection behind a service or
repository rather than placing the new project's role/relation rules directly
in the generic mail jobs.

## Current review findings

The implementation is usable and its CSV/inline-image tests pass, but it is not
a full campaign-management product.

1. There is no campaign table, delivery status, sent/failed counters, audit
   history, cancellation, preview/test-send workflow, or resumable campaign UI.
2. There is no unsubscribe/suppression-list handling. Add this before using the
   feature for marketing mail or where consent/anti-spam rules apply.
3. The two-second spacing is hard-coded, not a global/provider-aware limiter.
   Multiple campaigns or workers can still exceed the SMTP provider's limit.
4. Uploaded images/documents are public and never expired or deleted. Add a
   retention policy and cleanup job. A public URL is returned immediately.
5. HTML is intentionally rendered raw in the email. Access must remain tightly
   restricted to trusted admins, and HTML sanitization should be considered.
6. Existing-user IDs should be validated as strict integer strings; the current
   `intval` conversion can normalize malformed input such as `1abc` to `1`.
7. CSV validation reports a successful queue operation even when the file has no
   valid recipient rows. Add campaign/import feedback if operators need certainty.
8. Custom/CSV recipients have no user record, so name placeholders become empty.
9. Candidate percentage boundaries are implemented as `<= 30`, `> 30 and <= 80`,
   and `> 80`; UI wording should describe those exact boundaries.
10. There are no controller feature tests for authorization, validation,
    scheduling, recipient lookup, personalization, attachments, or SMTP failure
    behavior.
11. Queue payloads contain full subject/body HTML. This is simple but duplicates
    campaign content across many queued jobs; a campaign table plus campaign ID
    would be more efficient and auditable at scale.

## Recommended porting order

1. Map the target project's user model, roles, verification field, candidate
   relation, profile calculation, sender settings, and admin authorization.
2. Install/build TinyMCE and Tagify assets, or replace them with equivalents
   already used by the target project.
3. Port the migration, service, mailable, jobs, controller, routes, views, menu,
   and permission entry.
4. Configure SMTP, `APP_URL`, public storage, and the database queue connection.
5. Run migrations and start a supervised worker, for example
   `php artisan queue:work database --tries=10 --timeout=1200`.
6. Port/adapt the tests and add controller and end-to-end tests.
7. Test with the `array` or log mailer first, then send a small real campaign to
   seed accounts before enabling broad audiences.
8. Add campaign tracking, suppression/unsubscribe logic, configurable rate
   limiting, and upload retention before production-scale marketing use.

## Review verification

The focused suite was run on 2026-09-30:

```text
php artisan test tests/Feature/BulkEmailCsvReaderTest.php \
  tests/Feature/BulkEmailCsvJobsTest.php \
  tests/Unit/BulkEmailInlineImageTest.php

Result: 4 passed, 1 deprecation-marked, 14 assertions.
```

The deprecation output comes from the older Laravel/dependency stack running on
the installed PHP version, not from a failing bulk-email assertion.
