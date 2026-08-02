<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('candidate_links') || ! Schema::hasTable('candidates') || ! Schema::hasTable('users')) {
            return;
        }

        $linksByColumn = [
            'facebook_url' => 'Facebook',
            'twitter_url' => 'Twitter',
            'linkedin_url' => 'LinkedIn',
        ];

        DB::table('candidates')
            ->join('users', 'users.id', '=', 'candidates.user_id')
            ->select([
                'candidates.id as candidate_id',
                'users.facebook_url',
                'users.twitter_url',
                'users.linkedin_url',
            ])
            ->orderBy('candidates.id')
            ->chunk(100, function ($candidates) use ($linksByColumn) {
                foreach ($candidates as $candidate) {
                    $sortOrder = (int) DB::table('candidate_links')
                        ->where('candidate_id', $candidate->candidate_id)
                        ->max('sort_order');

                    foreach ($linksByColumn as $column => $platform) {
                        $url = trim((string) ($candidate->{$column} ?? ''));

                        if ($url === '') {
                            continue;
                        }

                        $exists = DB::table('candidate_links')
                            ->where('candidate_id', $candidate->candidate_id)
                            ->where('platform', $platform)
                            ->exists();

                        if ($exists) {
                            continue;
                        }

                        $sortOrder++;
                        DB::table('candidate_links')->insert([
                            'candidate_id' => $candidate->candidate_id,
                            'platform' => $platform,
                            'url' => $url,
                            'sort_order' => $sortOrder,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            });
    }

    public function down(): void
    {
        //
    }
};
