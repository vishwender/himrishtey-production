<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $schema = Schema::connection($this->getConnection());
        if ($schema->hasTable('member_activity_history')) {
            return;
        }
        $schema->create('member_activity_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id')->index();
            $table->unsignedBigInteger('target_member_id')->nullable()->index();
            $table->string('source');
            $table->unsignedBigInteger('source_id')->nullable();
            $table->string('event');
            $table->json('metadata')->nullable();
            $table->timestamp('created_at')->index();
        });
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('member_activity_history');
    }
};
