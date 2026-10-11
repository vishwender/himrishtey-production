<?php

use App\Services\SupportedRoles;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        app(SupportedRoles::class)->sync();
    }

    public function down(): void
    {
        // Previous custom permission assignments cannot be reconstructed safely.
    }
};
