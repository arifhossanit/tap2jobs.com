<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->string('experience_unit', 20)->default('year')->after('experience');
            $table->string('experience_requirement', 100)->default('0')->after('experience_unit');
            $table->boolean('freshers_encouraged')->default(false)->after('experience_requirement');
        });

        DB::table('jobs')->orderBy('id')->chunkById(100, function ($jobs) {
            foreach ($jobs as $job) {
                $years = (int) $job->experience;
                $months = (int) $job->experience_months;

                if ($years > 0 && $months > 0) {
                    $unit = 'month_year';
                    $requirement = $years.' years '.$months.' months';
                } elseif ($months > 0) {
                    $unit = 'month';
                    $requirement = (string) $months;
                } else {
                    $unit = 'year';
                    $requirement = (string) $years;
                }

                DB::table('jobs')->where('id', $job->id)->update([
                    'experience_unit' => $unit,
                    'experience_requirement' => $requirement,
                    'freshers_encouraged' => (bool) $job->is_fresher,
                ]);
            }
        });

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['experience_months', 'is_fresher']);
        });
    }

    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->unsignedTinyInteger('experience_months')->default(0)->after('experience');
            $table->boolean('is_fresher')->default(false)->after('experience_months');
        });

        DB::table('jobs')->update(['is_fresher' => DB::raw('freshers_encouraged')]);

        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn(['experience_unit', 'experience_requirement', 'freshers_encouraged']);
        });
    }
};
