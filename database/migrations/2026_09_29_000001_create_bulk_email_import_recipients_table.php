<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bulk_email_import_recipients', function (Blueprint $table) {
            $table->id();
            $table->uuid('import_id');
            $table->string('email');
            $table->timestamp('queued_at')->nullable();

            $table->unique(['import_id', 'email']);
            $table->index(['import_id', 'queued_at', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bulk_email_import_recipients');
    }
};
