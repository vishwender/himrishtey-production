<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('member_remember_tokens')) {
            Schema::create('member_remember_tokens', function ($table): void {
                $table->unsignedBigInteger('member_id')->primary();
                $table->string('token');
                $table->timestamp('updated_at');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('member_remember_tokens')) {
            Schema::drop('member_remember_tokens');
        }
    }
};
