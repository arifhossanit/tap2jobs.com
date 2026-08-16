<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFAQRequest;
use App\Http\Requests\UpdateFAQRequest;
use App\Models\FAQ;
use App\Models\FAQCategory;
use App\Repositories\FAQRepository;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FAQController extends AppBaseController
{
    /** @var FAQRepository */
    private $FAQRepository;

    public function __construct(FAQRepository $FAQRepository)
    {
        $this->FAQRepository = $FAQRepository;
    }

    /**
     * Display a listing of the FAQ.
     *
     * @return Factory|View
     *
     * @throws Exception
     */
    public function index(): View
    {
        $hasFaqCategories = Schema::hasTable('faq_categories');

        $categories = $hasFaqCategories
            ? FAQCategory::with(['faqs' => function ($query) {
                $query->orderBy('sort_order')->orderBy('id');
            }])->orderBy('audience')->orderBy('sort_order')->orderBy('name')->get()
            : collect();

        $uncategorizedFaqs = FAQ::whereNull('faq_category_id')->orderBy('sort_order')->orderBy('id')->get();

        $faqCategories = $categories->mapWithKeys(function ($cat) {
            $audienceLabel = ucfirst($cat->audience ?? 'Candidate');
            return [$cat->id => "{$cat->localizedName()} ({$audienceLabel})"];
        });

        return view('faqs.index', compact('categories', 'uncategorizedFaqs', 'faqCategories'));
    }

    /**
     * Store a newly created FAQ in storage.
     */
    public function store(CreateFAQRequest $request): JsonResponse
    {
        $input = $request->all();
        $input['title'] = $input['title_en'] ?? $input['title_bn'];
        $input['description'] = $input['description_en'] ?? $input['description_bn'];

        $this->FAQRepository->create($input);

        return $this->sendSuccess(__('messages.flash.faqs_save'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FAQ $faq): JsonResponse
    {
        return $this->sendResponse($faq, 'FAQs Retrieved Successfully.');
    }

    /**
     * Show the form for editing the specified FAQ.
     */
    public function show(FAQ $faq): JsonResponse
    {
        return $this->sendResponse($faq, 'FAQs Retrieved Successfully.');
    }

    /**
     * Update the specified FAQ in storage.
     */
    public function update(UpdateFAQRequest $request, FAQ $faq): JsonResponse
    {
        $input = $request->all();
        $input['title'] = $input['title_en'] ?? $input['title_bn'];
        $input['description'] = $input['description_en'] ?? $input['description_bn'];

        $this->FAQRepository->update($input, $faq->id);

        return $this->sendSuccess(__('messages.flash.faqs_update'));
    }

    /**
     * Remove the specified FAQ from storage.
     *
     * @throws Exception
     */
    public function destroy(FAQ $faq): JsonResponse
    {
        $faq->delete();

        return $this->sendSuccess(__('messages.flash.faqs_delete'));
    }

    /**
     * Store a newly created FAQ Category in storage.
     */
    public function storeCategory(Request $request): JsonResponse
    {
        $request->validate([
            'name_en' => [
                'required',
                'string',
                'max:191',
                \Illuminate\Validation\Rule::unique('faq_categories', 'name')->where(function ($query) use ($request) {
                    return $query->where('audience', $request->audience);
                })
            ],
            'name_bn' => 'required|string|max:191',
            'audience' => 'required|in:candidate,employer',
            'icon' => 'nullable|string|max:191',
        ], [
            'name_en.unique' => 'A category with this name already exists for this audience.',
        ]);

        $slug = \Illuminate\Support\Str::slug($request->name_en);
        $count = FAQCategory::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . time();
        }

        FAQCategory::create([
            'name' => $request->name_en,
            'name_en' => $request->name_en,
            'name_bn' => $request->name_bn,
            'slug' => $slug,
            'audience' => $request->audience,
            'icon' => $request->icon ?: 'fa-solid fa-folder-open',
        ]);

        return $this->sendSuccess('FAQ Category created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ Category.
     */
    public function editCategory(FAQCategory $faqCategory): JsonResponse
    {
        return $this->sendResponse($faqCategory, 'FAQ Category retrieved successfully.');
    }

    /**
     * Update the specified FAQ Category in storage.
     */
    public function updateCategory(Request $request, FAQCategory $faqCategory): JsonResponse
    {
        $request->validate([
            'name_en' => [
                'required',
                'string',
                'max:191',
                \Illuminate\Validation\Rule::unique('faq_categories', 'name')->where(function ($query) use ($request) {
                    return $query->where('audience', $request->audience);
                })->ignore($faqCategory->id)
            ],
            'name_bn' => 'required|string|max:191',
            'audience' => 'required|in:candidate,employer',
            'icon' => 'nullable|string|max:191',
        ], [
            'name_en.unique' => 'A category with this name already exists for this audience.',
        ]);

        $faqCategory->update([
            'name' => $request->name_en,
            'name_en' => $request->name_en,
            'name_bn' => $request->name_bn,
            'audience' => $request->audience,
            'icon' => $request->icon ?: 'fa-solid fa-folder-open',
        ]);

        return $this->sendSuccess('FAQ Category updated successfully.');
    }

    /**
     * Remove the specified FAQ Category from storage.
     */
    public function destroyCategory(FAQCategory $faqCategory): JsonResponse
    {
        FAQ::where('faq_category_id', $faqCategory->id)->update(['faq_category_id' => null]);
        $faqCategory->delete();

        return $this->sendSuccess('FAQ Category deleted successfully.');
    }

    /**
     * Toggle active status of a category.
     */
    public function toggleCategoryStatus(FAQCategory $faqCategory): JsonResponse
    {
        $faqCategory->update([
            'is_active' => !$faqCategory->is_active,
        ]);

        $statusMessage = $faqCategory->is_active
            ? 'FAQ Category activated successfully.'
            : 'FAQ Category deactivated successfully.';

        return $this->sendSuccess($statusMessage);
    }

    /**
     * Update sorting order of categories.
     */
    public function updateCategoryOrder(Request $request): JsonResponse
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:faq_categories,id',
        ]);

        foreach ($request->order as $index => $categoryId) {
            FAQCategory::where('id', $categoryId)->update(['sort_order' => $index + 1]);
        }

        return $this->sendSuccess('FAQ Category sorting order updated successfully.');
    }
}
