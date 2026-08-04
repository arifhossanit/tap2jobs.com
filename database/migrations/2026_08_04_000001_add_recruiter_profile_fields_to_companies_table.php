<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('company_name_bn')->nullable()->after('ceo');
            $table->text('company_summary')->nullable()->after('details');
            $table->text('company_summary_bn')->nullable()->after('company_summary');
            $table->string('trade_license_no', 100)->nullable()->after('website');
            $table->string('rl_no', 100)->nullable()->after('trade_license_no');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'company_name_bn',
                'company_summary',
                'company_summary_bn',
                'trade_license_no',
                'rl_no',
            ]);
        });
    }
};
