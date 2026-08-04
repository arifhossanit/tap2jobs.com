<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('billing_phone')->nullable()->after('location2');
            $table->string('billing_region_code', 10)->nullable()->after('billing_phone');
            $table->string('billing_email')->nullable()->after('billing_region_code');
            $table->boolean('has_disability_facilities')->default(false)->after('billing_email');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'billing_phone',
                'billing_region_code',
                'billing_email',
                'has_disability_facilities',
            ]);
        });
    }
};
