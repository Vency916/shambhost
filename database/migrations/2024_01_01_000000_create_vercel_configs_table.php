<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vercel_configs', function (Blueprint $table) {
            $table->id();
            $table->string('project_id')->unique();
            $table->text('api_token'); // Encrypted
            $table->json('environment_mapping')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vercel_configs');
    }
};
