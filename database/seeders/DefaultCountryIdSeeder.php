<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultCountryIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exists = Setting::where('key', 'default_country_id')->exists();

        if (! $exists) {
            Setting::create(['key' => 'default_country_id', 'value' => '']);
        }
    }
}
