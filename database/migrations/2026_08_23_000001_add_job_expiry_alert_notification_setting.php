<?php

use App\Models\NotificationSetting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        NotificationSetting::updateOrCreate(
            ['key' => 'JOB_EXPIRY_ALERT'],
            ['type' => 'admin', 'value' => true]
        );
    }

    public function down(): void
    {
        NotificationSetting::where('key', 'JOB_EXPIRY_ALERT')->delete();
    }
};
