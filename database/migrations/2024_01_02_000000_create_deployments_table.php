<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('deployments', function (Blueprint $table) {
            $table->id();
            $table->string('vercel_deployment_id')->unique();
            $table->string('project_id');
            $table->string('url')->nullable(); // The preview URL
            $table->string('state'); // READY, ERROR, BUILDING, CANCELED
            $table->string('branch')->nullable();
            $table->timestamp('created_at_vercel')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('deployments');
    }
};
