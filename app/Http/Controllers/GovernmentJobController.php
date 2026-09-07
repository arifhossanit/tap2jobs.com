<?php

namespace App\Http\Controllers;

use App\Models\GovernmentJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GovernmentJobController extends AppBaseController
{
    public function index(Request $request): View
    {
        $governmentJobs = GovernmentJob::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('organization_name', 'like', "%{$search}%")
                        ->orWhere('source_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('government_jobs.index', compact('governmentJobs'));
    }

    public function create(): View
    {
        return view('government_jobs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $data = $this->storeCircular($request, $data);
        GovernmentJob::query()->create($data);

        return redirect()->route('admin.government-jobs.index')->with('success', 'Government job created successfully.');
    }

    public function edit(GovernmentJob $governmentJob): View
    {
        return view('government_jobs.edit', compact('governmentJob'));
    }

    public function update(Request $request, GovernmentJob $governmentJob): RedirectResponse
    {
        $data = $this->validatedData($request, false);

        if ($request->hasFile('circular_file')) {
            $data = $this->storeCircular($request, $data);
            Storage::disk('public')->delete($governmentJob->circular_path);
        }

        $governmentJob->update($data);

        return redirect()->route('admin.government-jobs.index')->with('success', 'Government job updated successfully.');
    }

    public function destroy(GovernmentJob $governmentJob): RedirectResponse
    {
        Storage::disk('public')->delete($governmentJob->circular_path);
        $governmentJob->delete();

        return redirect()->route('admin.government-jobs.index')->with('success', 'Government job deleted successfully.');
    }

    public function publicIndex(Request $request): View
    {
        $governmentJobs = GovernmentJob::query()
            ->where('is_published', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->input('search'));
                $query->where(function ($query) use ($search) {
                    $query->where('title', 'like', "%{$search}%")
                        ->orWhere('organization_name', 'like', "%{$search}%");
                });
            })
            ->orderByDesc('published_at')
            ->latest('id')
            ->paginate(12)
            ->withQueryString();

        return view('front_web.government_jobs.index', compact('governmentJobs'));
    }

    public function publicShow(GovernmentJob $governmentJob): View
    {
        abort_unless($governmentJob->is_published, 404);

        return view('front_web.government_jobs.show', compact('governmentJob'));
    }

    private function validatedData(Request $request, bool $fileRequired = true): array
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'organization_name' => ['required', 'string', 'max:255'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'published_at' => ['nullable', 'date'],
            'application_deadline' => ['nullable', 'date', 'after_or_equal:published_at'],
            'circular_file' => [$fileRequired ? 'required' : 'nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:20480'],
            'application_url' => ['nullable', 'url', 'max:2048'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        unset($data['circular_file']);
        $data['is_published'] = $request->boolean('is_published');

        return $data;
    }

    private function storeCircular(Request $request, array $data): array
    {
        $file = $request->file('circular_file');
        $data['circular_path'] = $file->store('government-jobs', 'public');
        $data['circular_mime_type'] = $file->getMimeType();

        return $data;
    }
}
