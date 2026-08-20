<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('required_degree_levels') && ! Schema::hasTable('education_degree_levels')) {
            Schema::rename('required_degree_levels', 'education_degree_levels');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('education_degree_levels') && ! Schema::hasTable('required_degree_levels')) {
            Schema::rename('education_degree_levels', 'required_degree_levels');
        }
    }
};
