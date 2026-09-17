<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GovernmentJob;
use App\Models\Job;
use App\Models\JobCategory;
use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    private const URLS_PER_SITEMAP = 45000;

    public function sitemapIndex(): Response
    {
        $sections = collect([
            'pages' => 1,
            'job-categories' => $this->categoryQuery()->count(),
            'jobs' => Job::query()->availableForPublic()->count(),
            'government-jobs' => $this->governmentJobQuery()->count(),
            'posts' => Post::query()->count(),
        ])->flatMap(function (int $count, string $type) {
            $pages = max(1, (int) ceil($count / self::URLS_PER_SITEMAP));

            return collect(range(1, $pages))->map(fn (int $page) => [
                'loc' => route('seo.sitemap.section', ['type' => $type, 'page' => $page]),
            ]);
        });

        return $this->xmlResponse('front_web.seo.sitemap-index', compact('sections'));
    }

    public function sitemapSection(string $type, int $page): Response
    {
        abort_unless($page > 0, 404);

        $entries = match ($type) {
            'pages' => $page === 1 ? $this->staticPages() : collect(),
            'job-categories' => $this->categoryEntries($page),
            'jobs' => $this->jobEntries($page),
            'government-jobs' => $this->governmentJobEntries($page),
            'posts' => $this->postEntries($page),
            default => abort(404),
        };

        abort_if($entries->isEmpty() && $page > 1, 404);

        return $this->xmlResponse('front_web.seo.sitemap', compact('entries'));
    }

    public function robots(): Response
    {
        $content = implode("\n", [
            'User-agent: *',
            'Allow: /',
            'Disallow: /admin/',
            'Disallow: /candidate/',
            'Disallow: /employer/',
            'Disallow: /get-jobs-search',
            '',
            'Sitemap: '.route('seo.sitemap.index'),
            '',
        ]);

        return response($content, 200, ['Content-Type' => 'text/plain; charset=UTF-8']);
    }

    private function staticPages()
    {
        return collect([
            'front.home',
            'front.search.jobs',
            'front.government-jobs.index',
            'front.company.lists',
            'front.categories',
            'front.blogs',
            'front.about.us',
            'candidate.faq',
            'employer.faq',
            'privacy.policy.list',
            'terms.conditions.list',
            'front.contact',
        ])->map(fn (string $routeName) => ['loc' => route($routeName)]);
    }

    private function categoryQuery(): Builder
    {
        return JobCategory::query()
            ->where('status', JobCategory::STATUS_ACTIVE)
            ->whereNotNull('slug')
            ->where('slug', '!=', '')
            ->whereHas('jobs', fn (Builder $query) => $query->availableForPublic());
    }

    private function governmentJobQuery(): Builder
    {
        return GovernmentJob::query()
            ->where('is_published', true)
            ->whereDate('application_deadline', '>=', now()->toDateString());
    }

    private function categoryEntries(int $page)
    {
        return $this->categoryQuery()
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->forPage($page, self::URLS_PER_SITEMAP)
            ->get()
            ->map(fn (JobCategory $category) => [
                'loc' => route('front.job-categories.show', $category),
                'lastmod' => $category->updated_at?->toAtomString(),
            ]);
    }

    private function jobEntries(int $page)
    {
        return Job::query()
            ->availableForPublic()
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->forPage($page, self::URLS_PER_SITEMAP)
            ->get()
            ->map(fn (Job $job) => [
                'loc' => $job->front_url,
                'lastmod' => $job->updated_at?->toAtomString(),
            ]);
    }

    private function governmentJobEntries(int $page)
    {
        return $this->governmentJobQuery()
            ->select(['id', 'slug', 'updated_at'])
            ->orderBy('id')
            ->forPage($page, self::URLS_PER_SITEMAP)
            ->get()
            ->map(fn (GovernmentJob $job) => [
                'loc' => route('front.government-jobs.show', $job),
                'lastmod' => $job->updated_at?->toAtomString(),
            ]);
    }

    private function postEntries(int $page)
    {
        return Post::query()
            ->select(['id', 'updated_at'])
            ->orderBy('id')
            ->forPage($page, self::URLS_PER_SITEMAP)
            ->get()
            ->map(fn (Post $post) => [
                'loc' => route('front.posts.details', $post),
                'lastmod' => $post->updated_at?->toAtomString(),
            ]);
    }

    private function xmlResponse(string $view, array $data): Response
    {
        return response()
            ->view($view, $data)
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}
