<?php

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            ProfileReferenceOption::TYPE_CONSULTATION_TYPE,
            ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD,
        ] as $type) {
            $tableName = ProfileReferenceOption::tableFor($type);

            if (Schema::hasTable($tableName)) {
                continue;
            }

            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('scope', 30)->default(ProfileReferenceOption::SCOPE_EMPLOYER);
                $table->string('label', 150);
                $table->string('value', 150);
                $table->unsignedInteger('sort_order')->default(0);
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->unique(['scope', 'value']);
                $table->index(['scope', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(ProfileReferenceOption::tableFor(ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD));
        Schema::dropIfExists(ProfileReferenceOption::tableFor(ProfileReferenceOption::TYPE_CONSULTATION_TYPE));
    }
};
