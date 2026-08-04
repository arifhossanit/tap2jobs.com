<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('industry_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 170)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::table('industries', function (Blueprint $table) {
            $table->foreignId('industry_type_id')->nullable()->after('id');
            $table->foreignId('created_by')->nullable()->after('is_default');

            $table->foreign('industry_type_id', 'industries_industry_type_id_foreign')
                ->references('id')->on('industry_types')->nullOnDelete();
            $table->foreign('created_by', 'industries_created_by_foreign')
                ->references('id')->on('users')->nullOnDelete();
        });

        $now = now();
        $typeNames = [
            'General',
            'Agro based Industry',
            'Architecture/ Engineering/ Construction',
            'Advertising/ Marketing',
            'Information Technology',
            'Manufacturing',
            'Banking/ Financial Services',
            'Healthcare/ Pharmaceutical',
            'Education/ Training',
            'Hospitality/ Travel',
        ];

        foreach ($typeNames as $sortOrder => $typeName) {
            DB::table('industry_types')->insert([
                'name' => $typeName,
                'sort_order' => $sortOrder,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $typeIds = DB::table('industry_types')->pluck('id', 'name');
        DB::table('industries')->update(['industry_type_id' => $typeIds['General']]);

        $keywordGroups = [
            'Agro based Industry' => ['agro', 'agri', 'rice', 'dairy', 'farm', 'fisher', 'hatchery', 'livestock', 'animal', 'plant', 'seed'],
            'Architecture/ Engineering/ Construction' => ['architect', 'engineering', 'construction', 'interior', 'real estate'],
            'Advertising/ Marketing' => ['advert', 'marketing', 'media', 'public relation'],
            'Information Technology' => ['technology', 'software', 'information technology', 'telecom', 'e-commerce', 'ecommerce'],
            'Manufacturing' => ['manufact', 'garment', 'textile', 'factory', 'plastic', 'steel', 'cement'],
            'Banking/ Financial Services' => ['bank', 'finance', 'financial', 'insurance', 'accounting', 'investment'],
            'Healthcare/ Pharmaceutical' => ['health', 'hospital', 'medical', 'pharma', 'clinic'],
            'Education/ Training' => ['education', 'school', 'college', 'university', 'training'],
            'Hospitality/ Travel' => ['hotel', 'restaurant', 'travel', 'tour', 'airline', 'airport', 'amusement'],
        ];

        foreach ($keywordGroups as $typeName => $keywords) {
            DB::table('industries')
                ->where(function ($query) use ($keywords) {
                    foreach ($keywords as $keyword) {
                        $query->orWhereRaw('LOWER(name) LIKE ?', ['%'.strtolower($keyword).'%']);
                    }
                })
                ->update(['industry_type_id' => $typeIds[$typeName]]);
        }
    }

    public function down(): void
    {
        Schema::table('industries', function (Blueprint $table) {
            $table->dropForeign('industries_industry_type_id_foreign');
            $table->dropForeign('industries_created_by_foreign');
            $table->dropColumn(['industry_type_id', 'created_by']);
        });

        Schema::dropIfExists('industry_types');
    }
};
