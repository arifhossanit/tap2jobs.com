<?php

namespace Database\Seeders;

use App\Models\CompanyCategory;
use Illuminate\Database\Seeder;

class CompanyCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Bronze', 'sort_order' => 1],
            ['name' => 'Silver', 'sort_order' => 2],
            ['name' => 'Gold', 'sort_order' => 3],
            ['name' => 'Diamond', 'sort_order' => 4],
            ['name' => 'Platinum I', 'sort_order' => 5],
            ['name' => 'Platinum II', 'sort_order' => 6],
            ['name' => 'Platinum III', 'sort_order' => 7],
        ];

        foreach ($categories as $category) {
            CompanyCategory::updateOrCreate(
                ['name' => $category['name']],
                $category + ['is_active' => true]
            );
        }
    }
}
