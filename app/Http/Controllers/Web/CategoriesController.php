<?php

namespace App\Http\Controllers\web;

use App\Http\Controllers\Controller;
use App\Repositories\WebHomeRepository;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriesController extends Controller
{
    /** @var WebHomeRepository */
    private $homeRepository;

    public function __construct(WebHomeRepository $homeRepository)
    {
        $this->homeRepository = $homeRepository;
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
}
