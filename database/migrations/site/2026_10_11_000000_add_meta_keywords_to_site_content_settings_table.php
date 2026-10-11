<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_content_settings', function (Blueprint $table): void {
            $table->text('meta_keywords')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('site_content_settings', function (Blueprint $table): void {
            $table->dropColumn('meta_keywords');
        });
    }
};
