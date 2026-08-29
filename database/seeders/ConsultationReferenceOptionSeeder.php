<?php

namespace Database\Seeders;

use App\Models\ProfileReferenceOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ConsultationReferenceOptionSeeder extends Seeder
{
    private array $options = [
        ProfileReferenceOption::TYPE_CONSULTATION_TYPE => [
            'software_product_demo' => 'Software/Product Demo',
            'pricing_discussion' => 'Pricing Discussion',
            'customization_request' => 'Customization Request',
            'technical_supporteneral_inquiry' => 'Technical Supporteneral Inquiry',
            'general_inquiry' => 'General Inquiry',
        ],
        ProfileReferenceOption::TYPE_CONSULTATION_CONTACT_METHOD => [
            'phone' => 'Phone',
            'email' => 'Email',
            'whatsapp' => 'WhatsApp',
        ],
    ];

    public function run(): void
    {
        foreach ($this->options as $type => $options) {
            $table = ProfileReferenceOption::tableFor($type);

            if (! Schema::hasTable($table)) {
                continue;
            }

            $sortOrder = 1;

            foreach ($options as $value => $label) {
                DB::table($table)->updateOrInsert(
                    [
                        'scope' => ProfileReferenceOption::SCOPE_EMPLOYER,
                        'value' => (string) $value,
                    ],
                    [
                        'label' => (string) $label,
                        'sort_order' => $sortOrder++,
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}
