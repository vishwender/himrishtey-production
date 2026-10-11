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
        // Removed custom roles and assignments cannot be reconstructed safely.
    }
};
