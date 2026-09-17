# Tap2Jobs SEO Implementation

এই document-এ Tap2Jobs project-এ এখন পর্যন্ত implement করা SEO কাজগুলো phase অনুযায়ী বর্ণনা করা হয়েছে। এটি development handover, deployment, QA এবং পরবর্তী SEO কাজের reference হিসেবে ব্যবহার করা যাবে।

## বর্তমান অবস্থা

| Phase | বিষয় | অবস্থা |
|---|---|---|
| Phase 1 | Global metadata, canonical এবং noindex foundation | Implemented |
| Phase 2 | Category slug এবং category landing pages | Implemented |
| Phase 3 | JobPosting schema এবং expired-job handling | Implemented |
| Phase 4 | Filter URL indexing policy | Implemented |
| Phase 5 | Sitemap, robots এবং Google Indexing API | Implemented; API credentials pending |
| Phase 6 | Search Console verification/testing | Deferred |
| Phase 7 | এখনো define/implement করা হয়নি | Pending |
| Phase 8 | Content এবং internal linking | Implemented |

---

## Phase 1 — Global metadata, canonical এবং noindex foundation

### উদ্দেশ্য

সব public frontend page-এর জন্য একটি common SEO foundation তৈরি করা, যাতে প্রতিটি page default বা page-specific title, description, canonical URL, robots directive এবং social-sharing metadata output করতে পারে।

### Implemented behavior

- Global default meta description যোগ করা হয়েছে।
- প্রতিটি page Blade section দিয়ে নিচের value override করতে পারে:
  - `title`
  - `meta_description`
  - `canonical_url`
  - `robots`
  - `og_title`
  - `og_description`
  - `og_type`
  - `og_image`
  - অতিরিক্ত `meta_tags`
- Default canonical query string ছাড়া current clean URL ব্যবহার করে।
- Open Graph এবং Twitter card metadata common layout থেকে output হয়।
- `<html lang>` active application locale অনুযায়ী `en` অথবা `bn` হয়।
- Login, registration, password, apply workflow এবং utility pageগুলো noindex policy-এর অন্তর্ভুক্ত।
- Candidate/employer authentication layout search index থেকে বাদ রাখা হয়েছে।
- পুরোনো global `googleJobSchema()` loop সরিয়ে job-specific schema শুধু job detail page-এ সীমাবদ্ধ করা হয়েছে।

### প্রধান ফাইল

- `config/seo.php`
- `resources/views/front_web/layouts/app.blade.php`
- `resources/views/layouts/auth.blade.php`
- `resources/views/front_web/jobs/job_details.blade.php`

### Expected output

```html
<title>Page title | Tap2Jobs</title>
<meta name="description" content="Page-specific or default description">
<meta name="robots" content="index,follow">
<link rel="canonical" href="https://tap2jobs.com/example">
```

---

## Phase 2 — Category slug এবং category landing pages

### উদ্দেশ্য

Search-friendly clean category URL তৈরি করা এবং একই jobs listing/filter UI-এর মধ্যে category-specific landing page পরিবেশন করা।

### URL structure

বর্তমান canonical URL:

```text
/jobs
/jobs/category/{category-slug}
```

Legacy URL permanent redirect হয়:

```text
/search-jobs                         → /jobs
/search-jobs/category/{slug}         → /jobs/category/{slug}
```

Redirect status: `301`। Legacy `/search-jobs` query parameters preserve করে।

### Database fields

`job_categories` table-এ যোগ করা হয়েছে:

- `slug`
- `seo_title`
- `meta_description`
- `seo_content`

Migration:

```text
database/migrations/2026_09_17_000001_add_seo_fields_to_job_categories_table.php
```

### Category model behavior

- নতুন category তৈরি হলে unique slug generate হয়।
- Slug edit করলে uniqueness নিশ্চিত করা হয়।
- Route model binding `{jobCategory:slug}` ব্যবহার করে।
- Inactive category landing page `404` দেয়।

### Category landing page

Category route আলাদা visual template ব্যবহার করে না। একই jobs page-এ:

- category filter pre-selected থাকে;
- category-specific H1 থাকে;
- category-specific title/description/canonical output হয়;
- pagination-aware canonical থাকে;
- Breadcrumb schema থাকে;
- admin-provided SEO content job results-এর নিচে render হয়।

### Admin support

Category edit modal-এ admin নিচের field edit করতে পারে:

- Slug
- SEO title
- Meta description
- Landing page SEO content

### প্রধান ফাইল

- `app/Models/JobCategory.php`
- `app/Http/Controllers/Web/CategoriesController.php`
- `app/Http/Requests/UpdateJobCategoryRequest.php`
- `app/Repositories/JobCategoryRepository.php`
- `resources/views/job_categories/edit_modal.blade.php`
- `resources/assets/js/job_categories/job_categories.js`
- `resources/views/front_web/jobs/index.blade.php`
- `resources/views/front_web/categories/partials/category_list.blade.php`
- `routes/web.php`

---

## Phase 3 — JobPosting schema এবং expired-job handling

### উদ্দেশ্য

Google Jobs-এর জন্য valid job structured data দেওয়া এবং expired/closed/suspended job যেন নতুন application বা search indexing-এর জন্য eligible না হয় তা নিশ্চিত করা।

### Public job eligibility

একটি job public/applyable হওয়ার জন্য সব condition সত্য হতে হবে:

1. `status === STATUS_OPEN`
2. `is_suspended === NOT_SUSPENDED`
3. `job_expiry_date` বর্তমান দিনের শেষ পর্যন্ত valid

এই logic centralize করা হয়েছে:

```php
$job->isApplyable();
Job::query()->availableForPublic();
```

### JobPosting JSON-LD

শুধু applyable job detail page-এ `JobPosting` schema output হয়। Schema-তে প্রয়োজনে থাকে:

- `title`
- `description`
- `identifier`
- `datePosted`
- `validThrough`
- canonical `url`
- `hiringOrganization`
- company URL/logo
- `employmentType`
- physical `jobLocation`
- remote হলে `jobLocationType: TELECOMMUTE`
- `applicantLocationRequirements`
- salary public হলে `baseSalary`

Expired, suspended, paused, closed বা draft job-এ:

- `robots = noindex,follow`
- `JobPosting` schema output হয় না
- apply action backend থেকেও reject হয়

Deadline-এর দিন job দিনের শেষ পর্যন্ত applyable থাকে।

### প্রধান ফাইল

- `app/Models/Job.php`
- `app/Http/Controllers/Web/JobController.php`
- `app/Http/Controllers/Web/JobApplicationController.php`
- `app/Repositories/JobRepository.php`
- `app/Repositories/WebHomeRepository.php`
- `app/Livewire/JobSearch.php`
- `resources/views/front_web/jobs/job_details.blade.php`
- `resources/views/front_web/layouts/app.blade.php`

---

## Phase 4 — Filter URL indexing policy

### উদ্দেশ্য

Jobs filter থেকে তৈরি অসংখ্য URL Google index-এ ঢুকে duplicate/thin page তৈরি করা বন্ধ করা, কিন্তু crawler-কে links follow করতে দেওয়া।

### Policy

| URL example | Robots | Canonical |
|---|---|---|
| `/jobs` | `index,follow` | `/jobs` |
| `/jobs?page=2` | `index,follow` | `/jobs?page=2` |
| `/jobs?location=Dhaka` | `noindex,follow` | `/jobs` |
| `/jobs?unexpected_filter=x` | `noindex,follow` | `/jobs` |
| `/jobs/category/it-and-software` | `index,follow` | একই clean category URL |
| `/jobs/category/it-and-software?page=2` | `index,follow` | একই page-2 URL |
| Category URL + filter | `noindex,follow` | clean category URL |
| `/jobs?utm_source=facebook` | `index,follow` | `/jobs` |

### Recognized filter parameters

Policy-তে search, keywords, category, location, type, company, salary, experience, career level, functional area, gender, skill, deadline, source, organization, overseas, work-from-home, fresher, featured এবং matching-related parameters অন্তর্ভুক্ত।

Jobs/category faceted route-এ allowlist-এর বাইরে যেকোনো unknown query parameter-ও noindex হয়। এতে future filter যোগ হলেও accidental indexing কমে।

### Tracking parameters

নিচের parameters page-কে noindex করে না, কিন্তু canonical URL থেকে বাদ যায়:

- `utm_source`
- `utm_medium`
- `utm_campaign`
- `utm_term`
- `utm_content`
- `gclid`
- `fbclid`
- `msclkid`

### প্রধান ফাইল

- `config/seo.php`
- `resources/views/front_web/layouts/app.blade.php`
- `resources/views/front_web/jobs/index.blade.php`

---

## Phase 5 — Sitemap, robots এবং Google Indexing API

### Dynamic sitemap

Public sitemap index:

```text
/sitemap.xml
```

Child sitemaps:

```text
/sitemaps/pages-1.xml
/sitemaps/job-categories-1.xml
/sitemaps/jobs-1.xml
/sitemaps/government-jobs-1.xml
/sitemaps/posts-1.xml
```

প্রতি sitemap সর্বোচ্চ 45,000 URL নেয়। URL বেশি হলে automatic page/chunk তৈরি হয়।

### Sitemap inclusion policy

Jobs sitemap:

- শুধু open
- non-suspended
- non-expired jobs

Category sitemap:

- active category
- valid slug
- অন্তত একটি active public job

Government jobs sitemap:

- published
- deadline শেষ হয়নি

Posts sitemap:

- public blog posts

Static sitemap-এ home, jobs, government jobs, company list, categories, blogs, about, FAQ, privacy, terms এবং contact page অন্তর্ভুক্ত।

### Dynamic robots.txt

`public/robots.txt` static file সরিয়ে Laravel route ব্যবহার করা হয়েছে:

```text
/robots.txt
```

Output-এর মূল policy:

```text
User-agent: *
Allow: /
Disallow: /admin/
Disallow: /candidate/
Disallow: /employer/
Disallow: /get-jobs-search

Sitemap: https://tap2jobs.com/sitemap.xml
```

Sitemap URL `APP_URL` থেকে generate হয়। তাই production-এ `APP_URL=https://tap2jobs.com` সঠিক থাকা জরুরি।

### Google Indexing API

Google policy অনুযায়ী integration শুধু eligible `JobPosting` URL-এর জন্য ব্যবহার করা হয়েছে। Home, category, blog বা সাধারণ listing URL Indexing API-তে পাঠানো হয় না।

Behavior:

- New/published/updated live job → `URL_UPDATED`
- Public job closed/suspended/removed → `URL_DELETED`
- Job deleted → `URL_DELETED`
- আগের দিন expired হওয়া job → scheduled `URL_DELETED`
- Duplicate notification 10 মিনিটের জন্য unique queue job দিয়ে কমানো হয়
- Retry backoff: 60, 300 এবং 900 seconds
- Batch command accidental quota overuse ঠেকাতে সর্বোচ্চ 200 URL নেয়
- Default configured batch size 180

Command:

```bash
php artisan seo:index-jobs --type=updated
php artisan seo:index-jobs --type=deleted
php artisan seo:index-jobs --type=expired
php artisan seo:index-jobs --type=updated --limit=100
```

Daily scheduler:

```text
00:15 → seo:index-jobs --type=expired
```

### API configuration

```dotenv
GOOGLE_INDEXING_ENABLED=false
GOOGLE_INDEXING_CREDENTIALS=storage/app/google-indexing-service-account.json
GOOGLE_INDEXING_QUEUE=default
GOOGLE_INDEXING_BATCH_SIZE=180
```

Credentials file public directory বা Git repository-তে রাখা যাবে না। Default `storage/app` path Git ignored।

### প্রধান ফাইল

- `app/Http/Controllers/Web/SeoController.php`
- `resources/views/front_web/seo/sitemap-index.blade.php`
- `resources/views/front_web/seo/sitemap.blade.php`
- `app/Services/GoogleIndexingService.php`
- `app/Jobs/NotifyGoogleIndexingApi.php`
- `app/Observers/JobObserver.php`
- `app/Console/Commands/SubmitJobsToGoogleIndexing.php`
- `app/Console/Kernel.php`
- `app/Providers/AppServiceProvider.php`
- `config/seo.php`
- `.env.example`
- `routes/web.php`
- `docs/seo-phase-5.md`

---

## Phase 6 — Search Console verification/testing

এই phase ইচ্ছাকৃতভাবে deferred করা হয়েছে। Code-side foundation প্রস্তুত, কিন্তু production deploy এবং Google account/DNS access ছাড়া phase সম্পন্ন করা যাবে না।

### পরে যা করতে হবে

1. Phase 1–5 production-এ deploy করতে হবে।
2. `https://tap2jobs.com/robots.txt` HTTP 200 নিশ্চিত করতে হবে।
3. `https://tap2jobs.com/sitemap.xml` HTTP 200 এবং valid XML নিশ্চিত করতে হবে।
4. Search Console-এ Domain property add করতে হবে।
5. Google-provided DNS TXT record দিয়ে ownership verify করতে হবে।
6. Search Console-এ `sitemap.xml` submit করতে হবে।
7. Active job, expired job, category এবং filter URL URL Inspection দিয়ে পরীক্ষা করতে হবে।
8. Active job Google Rich Results Test-এ `Job posting` হিসেবে validate করতে হবে।
9. Service-account email verified Search Console property-এর permission/ownership-এ যোগ করতে হবে।

শেষ check-এর সময় production `https://tap2jobs.com/sitemap.xml` এখনো 404 ছিল এবং production robots পুরোনো static output দিচ্ছিল। অর্থাৎ নতুন SEO code তখনও production-এ deploy হয়নি।

---

## Phase 7 — Pending

Phase 7-এর scope এখনো নির্ধারণ বা implement করা হয়নি। পরবর্তী roadmap-এ Phase 7 আলাদাভাবে define করতে হবে।

---

## Phase 8 — Content এবং internal linking

### উদ্দেশ্য

Indexable pageগুলোকে শুধু listing/filter page না রেখে useful context দেওয়া এবং গুরুত্বপূর্ণ pageগুলোর মধ্যে crawlable, descriptive internal links তৈরি করা।

### Category landing page content

Job results এবং pagination-এর পরে category SEO content section থাকে। এটি:

- category-specific heading দেখায়;
- admin-এর `seo_content` বা description render করে;
- content না থাকলে localized fallback paragraph দেখায়;
- active related category links দেখায়;
- related category-এর active job count দেখায়।

বর্তমানে related categories active job count অনুযায়ী সর্বোচ্চ 6টি দেখানো হয়। এটি crawlability উন্নত করে, তবে semantic relevance-এর জন্য ভবিষ্যতে manual category relationship ব্যবহার করা ভালো।

### SEO content sanitization

Admin-provided category content rawভাবে output করার আগে `SeoContentSanitizer` দিয়ে পরিষ্কার করা হয়। Allowed content:

- paragraphs
- H2–H4 headings
- strong/emphasis
- ordered/unordered lists
- blockquote
- safe links
- line breaks

Script, event-handler এবং unsafe URL remove হয়।

### Job details internal links

Job details page-এ যোগ/উন্নত করা হয়েছে:

- visible breadcrumb: Home → Jobs → Category → Job
- `BreadcrumbList` JSON-LD
- job title একমাত্র page H1
- company name থেকে company profile link
- category name থেকে clean category landing page link
- related job card থেকে category link
- related jobs শুধু active, non-suspended, non-expired data থেকে আসে

### Blog internal links এবং metadata

Blog detail page-এ:

- article title page H1
- dynamic meta title
- content-derived meta description
- self canonical
- Open Graph article metadata
- blog image social preview
- visible breadcrumb
- `BreadcrumbList` JSON-LD
- সব assigned blog category badge clickable
- একই category-এর সর্বোচ্চ 3টি related career article
- একই category article কম হলে latest articles দিয়ে fallback
- “View all articles” link

### Homepage

Homepage-এর category links আগে থেকেই real crawlable `<a href>` ব্যবহার করছিল। তাই duplicate section যোগ করা হয়নি। Existing links clean category landing URL-এ point করে।

### English/Bangla toggle

Phase 8-এর নতুন interface text translation file-এ নেওয়া হয়েছে। Toggle করলে নিচের textগুলো English/Bangla হয়:

- category landing title/fallback description
- about-category heading
- related-category heading এবং ARIA label
- category/company “Jobs/চাকরি” suffix
- related career articles
- view all articles
- read article

Translation files:

- `lang/en/web.php`
- `lang/bn/web.php`

### Language limitation

Database content বর্তমানে single-language field:

- category name
- `seo_title`
- `meta_description`
- `seo_content`
- job title/description
- blog title/content

এগুলো admin যে ভাষায় লিখবে সেই ভাষাতেই দেখা যাবে। সম্পূর্ণ bilingual content-এর জন্য future migration-এ আলাদা `_en`/`_bn` fields অথবা translation table প্রয়োজন।

### প্রধান ফাইল

- `app/Services/SeoContentSanitizer.php`
- `app/Http/Controllers/Web/CategoriesController.php`
- `app/Http/Controllers/Web/JobController.php`
- `app/Repositories/PostRepository.php`
- `resources/views/front_web/jobs/index.blade.php`
- `resources/views/front_web/jobs/job_details.blade.php`
- `resources/views/front_web/blogs/blogs_details.blade.php`
- `public/css/front-pages.css`
- `lang/en/web.php`
- `lang/bn/web.php`

---

## SEO URL behavior summary

| Page type | Index policy | Structured data | Sitemap |
|---|---|---|---|
| Homepage | Index | — | Yes |
| Clean jobs listing | Index | — | Yes |
| Jobs pagination | Index, self-canonical | — | No direct generated entry |
| Filtered jobs | Noindex, follow | — | No |
| Active category landing | Index | Breadcrumb | Yes, if active jobs exist |
| Filtered category landing | Noindex, follow | Breadcrumb | No |
| Active job detail | Index | JobPosting + Breadcrumb | Yes |
| Expired/closed/suspended job | Noindex, follow | Breadcrumb only; no JobPosting | No |
| Government job | Index when published/current | Existing page metadata | Yes |
| Blog listing | Index | — | Yes |
| Blog detail | Index | Breadcrumb | Yes |
| Login/register/apply utility | Noindex | — | No |

---

## Validation already performed

### Phase 4 URL tests

- Clean `/jobs`: `index,follow`, correct canonical
- `/jobs?page=2`: indexable self-canonical
- Known filter URL: `noindex,follow`, clean canonical
- Unknown filter URL: `noindex,follow`, clean canonical
- Clean category URL: indexable
- Category pagination: self-canonical
- Filtered category pagination: noindex, clean category canonical
- Tracking parameters: canonical থেকে removed

### Sitemap tests

- `/robots.txt`: HTTP 200, text/plain
- `/sitemap.xml`: HTTP 200, valid sitemap index
- Static page sitemap: valid XML
- Job sitemap: valid XML
- Category sitemap: valid XML
- Government job sitemap: valid XML
- Blog sitemap: valid XML
- Invalid child sitemap page: HTTP 404

Local validation-এর সময় sitemap index-এ 5টি child sitemap এবং job sitemap-এ 477টি eligible job পাওয়া গিয়েছিল। এটি database data পরিবর্তনের সঙ্গে বদলাবে।

### Phase 8 render tests

- Category page: HTTP 200, exactly one H1, Breadcrumb schema
- Job details: HTTP 200, exactly one H1, category/company links, Breadcrumb schema
- Blog details: HTTP 200, exactly one H1, dynamic metadata, related articles, Breadcrumb schema
- English → Bangla simulated session toggle pass
- Blade compilation pass
- Modified PHP files syntax validation pass
- `git diff --check` pass

Project-এর full existing test suite-এর শেষ run-এ 103টি test pass এবং 8টি unrelated pre-existing/static assertion test fail করেছিল। SEO live response validation আলাদাভাবে pass করেছে।

---

## Production deployment checklist

### Application deployment

```bash
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Production `.env`:

```dotenv
APP_URL=https://tap2jobs.com
APP_ENV=production
APP_DEBUG=false
```

### Indexing API activation

API এখন default disabled। Credentials প্রস্তুত না হওয়া পর্যন্ত disabled রাখুন।

```dotenv
GOOGLE_INDEXING_ENABLED=true
GOOGLE_INDEXING_CREDENTIALS=storage/app/google-indexing-service-account.json
GOOGLE_INDEXING_QUEUE=default
GOOGLE_INDEXING_BATCH_SIZE=180
```

Real queue worker চালাতে হবে। Production-এ external API work-এর জন্য `QUEUE_CONNECTION=sync` ব্যবহার না করাই ভালো।

Scheduler cron:

```cron
* * * * * cd /path/to/tap2jobs && php artisan schedule:run >> /dev/null 2>&1
```

Initial live-job notification:

```bash
php artisan seo:index-jobs --type=updated
```

### Post-deployment smoke test

```text
https://tap2jobs.com/robots.txt
https://tap2jobs.com/sitemap.xml
https://tap2jobs.com/sitemaps/jobs-1.xml
https://tap2jobs.com/jobs
https://tap2jobs.com/jobs/category/{valid-slug}
https://tap2jobs.com/job-details/{active-job-id}
```

প্রতিটি URL-এর HTTP status, canonical, robots এবং structured data পরীক্ষা করতে হবে।

---

## Known limitations এবং next recommendations

1. Search Console verification এবং sitemap submission এখনো pending।
2. Google Indexing API credentials এখনো production-এ configure করা হয়নি।
3. Production deployment না হওয়া পর্যন্ত live domain নতুন sitemap/robots দেখাবে না।
4. Category SEO fields bilingual নয়।
5. Related categories বর্তমানে job count অনুযায়ী আসে; semantic/manual relationship নেই।
6. Category-specific meaningful content admin-কে লিখতে হবে। শুধু category name বা খুব ছোট generic content SEO value কম দেবে।
7. Location/type filter pages noindex। ভবিষ্যতে location/type-এর জন্য indexable landing page প্রয়োজন হলে clean slug route এবং unique content আলাদাভাবে implement করতে হবে।
8. Search Console coverage, Core Web Vitals এবং rich-result reports production launch-এর পর monitor করতে হবে।

---

## গুরুত্বপূর্ণ নীতি

- সব filter page indexable করা যাবে না।
- Sitemap-এ noindex, expired বা non-public URL রাখা যাবে না।
- Indexing API সাধারণ page-এর shortcut নয়; শুধু supported JobPosting URL-এ ব্যবহার করতে হবে।
- JobPosting schema page-এর visible content-এর সঙ্গে মিলতে হবে।
- Category content মানুষের জন্য useful এবং category-specific হতে হবে।
- Internal link descriptive এবং relevant হতে হবে; শুধু link count বাড়ানোর জন্য unrelated link দেওয়া যাবে না।
- Canonical URL indexing guarantee নয়; robots policy, sitemap এবং internal links-এর সঙ্গে consistent থাকতে হবে।
