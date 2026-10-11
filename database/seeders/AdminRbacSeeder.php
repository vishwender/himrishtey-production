<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Role;
use App\Models\Site;
use App\Services\SupportedRoles;
use Illuminate\Database\Seeder;

class AdminRbacSeeder extends Seeder
{
    public function run(): void
    {
        app(SupportedRoles::class)->sync();

        // Preserve the password and status of an existing bootstrap account.
        $admin = Admin::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Super Admin',
                'profile_id' => 1,
                'password' => 'ChangeMe@123',
                'status' => true,
            ]
        );

        $admin->roles()->syncWithoutDetaching([
            Role::where('slug', 'super-admin')->value('id'),
        ]);
        $admin->sites()->syncWithoutDetaching(
            Site::where('status', true)->pluck('id')->all()
        );

        $this->command?->info('Roles configured: Super Admin, Admin, Relationship Manager, Content Manager.');
    }
}
