<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());
        if ($schema->hasColumn('site_content_settings', 'meta_keywords')) {
            return;
        }

        $schema->table('site_content_settings', function (Blueprint $table): void {
            $table->text('meta_keywords')->nullable();
        });
    }

    public function down(): void
    {
        $schema = Schema::connection($this->getConnection());
        if (! $schema->hasColumn('site_content_settings', 'meta_keywords')) {
            return;
        }

        $schema->table('site_content_settings', function (Blueprint $table): void {
            $table->dropColumn('meta_keywords');
        });
    }
};
