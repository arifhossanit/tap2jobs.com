<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('job_locations')) {
            Schema::create('job_locations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('job_id');
                $table->unsignedBigInteger('country_id')->nullable();
                $table->unsignedBigInteger('state_id')->nullable();
                $table->unsignedBigInteger('city_id')->nullable();
                $table->unsignedBigInteger('thana_id')->nullable();
                $table->string('city_village_name')->nullable();
                $table->text('address')->nullable();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();

                $table->foreign('job_id')->references('id')->on('jobs')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');
                $table->foreign('country_id')->references('id')->on('countries')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreign('state_id')->references('id')->on('states')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreign('city_id')->references('id')->on('cities')
                    ->onUpdate('cascade')
                    ->onDelete('set null');
                $table->foreign('thana_id')->references('id')->on('thanas')
                    ->onUpdate('cascade')
                    ->onDelete('set null');

                $table->index(['job_id', 'is_primary']);
            });
        }

        $this->backfillPrimaryLocations();
    }

    public function down(): void
    {
        Schema::dropIfExists('job_locations');
    }

    private function backfillPrimaryLocations(): void
    {
        DB::table('jobs')
            ->select(['id', 'country_id', 'state_id', 'city_id', 'thana_id', 'city_village_name', 'address'])
            ->orderBy('id')
            ->chunkById(100, function ($jobs) {
                $jobIds = $jobs->pluck('id')->all();
                $existingJobIds = DB::table('job_locations')
                    ->whereIn('job_id', $jobIds)
                    ->pluck('job_id')
                    ->all();

                $countryIds = $this->validIds('countries', $jobs->pluck('country_id')->filter()->all());
                $stateIds = $this->validIds('states', $jobs->pluck('state_id')->filter()->all());
                $cityIds = $this->validIds('cities', $jobs->pluck('city_id')->filter()->all());
                $thanaIds = Schema::hasTable('thanas')
                    ? $this->validIds('thanas', $jobs->pluck('thana_id')->filter()->all())
                    : [];

                $now = now();
                $rows = [];

                foreach ($jobs as $job) {
                    if (in_array($job->id, $existingJobIds)) {
                        continue;
                    }

                    if (! $job->country_id && ! $job->state_id && ! $job->city_id && ! $job->thana_id && ! $job->city_village_name && ! $job->address) {
                        continue;
                    }

                    $rows[] = [
                        'job_id' => $job->id,
                        'country_id' => in_array($job->country_id, $countryIds) ? $job->country_id : null,
                        'state_id' => in_array($job->state_id, $stateIds) ? $job->state_id : null,
                        'city_id' => in_array($job->city_id, $cityIds) ? $job->city_id : null,
                        'thana_id' => in_array($job->thana_id, $thanaIds) ? $job->thana_id : null,
                        'city_village_name' => $job->city_village_name,
                        'address' => $job->address,
                        'is_primary' => true,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($rows !== []) {
                    DB::table('job_locations')->insert($rows);
                }
            });
    }

    private function validIds(string $table, array $ids): array
    {
        $ids = array_values(array_unique(array_filter($ids)));

        if ($ids === []) {
            return [];
        }

        return DB::table($table)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();
    }
};