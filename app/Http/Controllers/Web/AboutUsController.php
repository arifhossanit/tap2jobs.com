<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Models\CmsServices;
use App\Models\FAQ;
use App\Models\FAQCategory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class AboutUsController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * @return Application|RedirectResponse|Redirector
     */
    public function FAQLists(): View
    {
        $faqLists = FAQ::tobase()->get();
        $settings = CmsServices::pluck('value', 'key');

        return view('front_web.about_us.index', compact('faqLists', 'settings'));
    }

    /**
     * Display Candidate FAQ page.
     *
     * @return View
     */
    public function candidateFaq(): View
    {
        return $this->frontFaq('candidate', __('messages.faq.candidate_faq'));
    }

    /**
     * Display Employer FAQ page.
     */
    public function employerFaq(): View
    {
        return $this->frontFaq('employer', __('messages.faq.employer_faq'));
    }

    private function frontFaq(
        string $audience,
        string $faqPageTitle,
        string $faqLayout = 'front_web.layouts.app',
        ?string $dashboardFaqHeader = null
    ): View
    {
        $hasFaqCategories = Schema::hasTable('faq_categories');
        $hasFaqCategoryColumn = Schema::hasColumn('faqs', 'faq_category_id');
        $hasFaqSortOrderColumn = Schema::hasColumn('faqs', 'sort_order');
        $hasFaqCategoryAudienceColumn = $hasFaqCategories && Schema::hasColumn('faq_categories', 'audience');

        $faqCategories = collect();
        if ($hasFaqCategories && $hasFaqCategoryColumn) {
            $faqCategories = FAQCategory::with(['faqs' => function ($query) use ($hasFaqSortOrderColumn) {
                $hasFaqSortOrderColumn
                    ? $query->orderBy('sort_order')->orderBy('id')
                    : $query->orderBy('id');
            }])
                ->when($hasFaqCategoryAudienceColumn, fn ($query) => $query->where('audience', $audience))
                ->when(Schema::hasColumn('faq_categories', 'is_active'), fn ($query) => $query->where('is_active', true))
                ->orderBy('sort_order')
                ->orderBy('id')
                ->get();
        }

        $faqQuery = FAQ::query();
        if ($hasFaqCategories && $hasFaqCategoryColumn) {
            $faqQuery->with('category');
        }
        $hasFaqSortOrderColumn
            ? $faqQuery->orderBy('sort_order')->orderBy('id')
            : $faqQuery->orderBy('id');

        $faqLists = $faqQuery->get();
        $settings = CmsServices::pluck('value', 'key');

        $isDashboardFaq = $faqLayout !== 'front_web.layouts.app';

        return view('front_web.candidate_faq.index', compact(
            'faqCategories',
            'faqLists',
            'settings',
            'faqPageTitle',
            'faqLayout',
            'isDashboardFaq',
            'dashboardFaqHeader'
        ));
    }
}
