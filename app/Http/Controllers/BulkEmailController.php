<?php

namespace App\Http\Controllers;

use App\Jobs\DispatchBulkEmail;
use App\Jobs\ImportBulkEmailCsv;
use App\Models\User;
use App\Services\BulkEmailCsvReader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BulkEmailController extends Controller
{
    public function index(): View
    {
        return view('bulk_email.index');
    }

    public function users(Request $request): JsonResponse
    {
        $term = trim((string) $request->query('q', ''));
        if (mb_strlen($term) < 2) {
            return response()->json([]);
        }

        $term = addcslashes($term, '%_\\');

        return response()->json(User::query()->setEagerLoads([])
            ->role(['Candidate', 'Employer'])
            ->whereNotNull('email_verified_at')
            ->where(function ($query) use ($term) {
                $query->where('email', 'like', "%{$term}%")
                    ->orWhere('first_name', 'like', "%{$term}%")
                    ->orWhere('last_name', 'like', "%{$term}%");
            })
            ->orderBy('first_name')
            ->limit(20)
            ->get(['id', 'first_name', 'last_name', 'email'])
            ->map(fn (User $user) => [
                'value' => (string) $user->id,
                'name' => trim($user->first_name.' '.$user->last_name),
                'email' => $user->email,
            ]));
    }

    public function send(Request $request, BulkEmailCsvReader $csvReader): RedirectResponse
    {
        $data = $request->validate([
            'target_type' => ['required', Rule::in(['candidate', 'employer', 'existing', 'custom', 'csv'])],
            'candidate_profile_filter' => ['nullable', Rule::in(['all', 'below_30', '30_to_below_80', '80_plus'])],
            'recipients' => ['required_if:target_type,existing,custom', 'array', 'max:1000'],
            'recipients.*' => ['required', 'string', 'max:255'],
            'csv_file' => ['required_if:target_type,csv', 'nullable', 'file', 'mimes:csv,txt', 'max:51200'],
            'schedule_at' => ['nullable', 'date_format:Y-m-d\TH:i', 'after:now'],
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:200000'],
        ]);

        if (trim(strip_tags($data['body'])) === '' && ! str_contains($data['body'], '<img')) {
            return back()->withInput()->withErrors(['body' => 'Please enter an email message.']);
        }

        $recipients = array_values(array_unique($data['recipients'] ?? []));
        if ($data['target_type'] === 'custom') {
            $validator = Validator::make(['emails' => $recipients], [
                'emails.*' => ['required', 'email:rfc', 'max:255'],
            ]);
            if ($validator->fails()) {
                return back()->withInput()->withErrors($validator);
            }
            $recipients = array_values(array_unique(array_map('strtolower', $recipients)));
        } elseif ($data['target_type'] === 'existing') {
            $ids = array_map('intval', $recipients);
            if (count($ids) !== count($recipients) || in_array(0, $ids, true)) {
                return back()->withInput()->withErrors(['recipients' => 'Choose verified users from the search results.']);
            }
            $verifiedCount = User::query()->role(['Candidate', 'Employer'])
                ->whereNotNull('email_verified_at')->whereIn('id', $ids)->count();
            if ($verifiedCount !== count($ids)) {
                return back()->withInput()->withErrors(['recipients' => 'Some selected users are not verified. Please select them again.']);
            }
            $recipients = $ids;
        }

        $scheduleAt = isset($data['schedule_at'])
            ? Carbon::createFromFormat('Y-m-d\TH:i', $data['schedule_at'], config('app.timezone'))
            : null;

        $candidateProfileFilter = $data['target_type'] === 'candidate'
            ? ($data['candidate_profile_filter'] ?? 'all')
            : 'all';

        if ($data['target_type'] === 'csv') {
            $emailColumn = $csvReader->emailColumn($request->file('csv_file')->getRealPath());
            if ($emailColumn === null) {
                return back()->withInput()->withErrors([
                    'csv_file' => 'The CSV must contain an email column in its first row.',
                ]);
            }

            $path = $request->file('csv_file')->store('bulk-email-imports', 'local');
            ImportBulkEmailCsv::dispatch((string) Str::uuid(), $path, $data['subject'], $data['body'])
                ->onConnection('database')
                ->delay($scheduleAt);

            return redirect()->route('bulk-email.index')->with('bulk_email_success', $scheduleAt
                ? 'CSV email import scheduled. Recipients will be validated and queued at the selected time.'
                : 'CSV uploaded. Recipients are being validated and queued in the background.');
        }

        DispatchBulkEmail::dispatch(
            $data['target_type'],
            $recipients,
            $data['subject'],
            $data['body'],
            $candidateProfileFilter
        )
            ->onConnection('database')
            ->delay($scheduleAt);

        return redirect()->route('bulk-email.index')->with('bulk_email_success', $scheduleAt
            ? 'Bulk email scheduled. It will be sent by the queue worker at the selected time.'
            : 'Bulk email queued for sending.');
    }

    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx', 'max:10240'],
        ]);

        $path = $request->file('file')->store('bulk-email', 'public');

        return response()->json([
            'url' => Storage::disk('public')->url($path),
            'name' => $request->file('file')->getClientOriginalName(),
        ]);
    }

}
