# SEO Phase 5 setup

## Public endpoints

- `/sitemap.xml` — sitemap index
- `/robots.txt` — crawler rules and sitemap discovery
- `/sitemaps/pages-1.xml` — static public pages
- `/sitemaps/job-categories-1.xml` — active category landing pages
- `/sitemaps/jobs-1.xml` — active, non-suspended, non-expired jobs
- `/sitemaps/government-jobs-1.xml` — published, non-expired government jobs
- `/sitemaps/posts-1.xml` — blog posts

Large sitemap sections are automatically split at 45,000 URLs.

## Google Indexing API activation

The Indexing API integration is intentionally limited to eligible `JobPosting`
detail URLs. Do not submit home, category, blog, or general listing URLs through
this API.

1. Enable the Google Indexing API in a Google Cloud project.
2. Create a service account and download its JSON key.
3. Add the service-account email as an owner of the verified Search Console
   property for the production domain.
4. Store the JSON outside the public directory, by default at:
   `storage/app/google-indexing-service-account.json`.
5. Configure production `.env`:

   ```dotenv
   GOOGLE_INDEXING_ENABLED=true
   GOOGLE_INDEXING_CREDENTIALS=storage/app/google-indexing-service-account.json
   GOOGLE_INDEXING_QUEUE=default
   GOOGLE_INDEXING_BATCH_SIZE=180
   ```

6. Run a real queue worker in production (do not use the `sync` driver for
   external API work) and ensure Laravel's scheduler runs every minute.
7. Submit the initial live-job batch:

   ```shell
   php artisan seo:index-jobs --type=updated
   ```

New/updated live jobs and removed/closed jobs are queued automatically. A daily
scheduled command submits URLs whose application deadline expired the previous
day. The batch command is capped at 200 URLs to avoid accidentally exceeding the
common default publishing quota.

## Search Console

Submit only the sitemap index URL (`https://your-domain/sitemap.xml`) in Search
Console. Child sitemaps are discovered from that index automatically.
