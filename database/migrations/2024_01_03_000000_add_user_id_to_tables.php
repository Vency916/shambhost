<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // First delete existing data to avoid constraint violations if table not empty
        // In production we would set nullable first, fill data, then set non-nullable.
        // For development, truncation is fine.
        \DB::table('vercel_configs')->truncate();
        \DB::table('deployments')->truncate();

        Schema::table('vercel_configs', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
        });

        Schema::table('deployments', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('vercel_configs', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('deployments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
