<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Models\JobCategory;
use App\Repositories\JobRepository;
use App\Repositories\WebHomeRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    /** @var WebHomeRepository */
    private $homeRepository;

    private $jobRepository;

    public function __construct(WebHomeRepository $homeRepository, JobRepository $jobRepository)
    {
        $this->homeRepository = $homeRepository;
        $this->jobRepository = $jobRepository;
    }

    public function index(Request $request): View
    {
        $search = trim((string) $request->get('search'));
        $jobCategories = $this->homeRepository->getAllJobCategories($search !== '' ? $search : null);

        if ($request->ajax()) {
            return view('front_web.categories.partials.category_list', compact('jobCategories', 'search'));
        }

        return view('front_web.categories.index', compact('jobCategories', 'search'));
    }

    public function show(JobCategory $jobCategory): View
    {
        abort_unless((int) $jobCategory->status === JobCategory::STATUS_ACTIVE, 404);

        $data = $this->jobRepository->prepareJobData();
        $data['input'] = ['categories' => $jobCategory->id];

        $data['seoCategory'] = $jobCategory;

        return view('front_web.jobs.index')->with($data);
    }
}
