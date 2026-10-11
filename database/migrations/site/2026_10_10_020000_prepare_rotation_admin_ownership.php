<?php

use App\Services\RotationAdminOwnership;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        app(RotationAdminOwnership::class)->prepare(DB::connection($this->getConnection()));
    }

    public function down(): void
    {
        // Preserve ownership data written by the admin application.
    }
};
