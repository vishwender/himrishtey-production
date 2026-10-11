<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Facades\DB;

class SupportedRoles
{
    public function sync(): void
    {
        DB::transaction(function () {
            $legacy = Role::where('slug', 'member-manager')->first();
            $adminRole = Role::where('slug', 'admin')->first();

            if ($legacy && ! $adminRole) {
                $legacy->update(['slug' => 'admin', 'name' => 'Admin']);
            } elseif ($legacy && $adminRole) {
                $adminRole->admins()->syncWithoutDetaching($legacy->admins()->pluck('admins.id')->all());
                $adminRole->permissions()->syncWithoutDetaching($legacy->permissions()->pluck('permissions.id')->all());
            }

            foreach (Role::SUPPORTED as $slug => $name) {
                $role = Role::firstOrCreate(['slug' => $slug], ['name' => $name]);
                $role->update(['name' => $name]);

                $role->permissions()->sync($this->permissions(RolePermissions::forRole($slug)));
            }

            // Remove obsolete definitions after every supported role has been synchronized.
            foreach (Permission::whereNotIn('slug', RolePermissions::all())->get() as $permission) {
                $permission->roles()->detach();
                $permission->delete();
            }

            // Accounts with no surviving role must be reassigned before signing in.
            Admin::whereDoesntHave('roles', fn ($query) => $query->whereIn('slug', array_keys(Role::SUPPORTED)))
                ->update(['status' => false]);

            $removed = Role::whereNotIn('slug', array_keys(Role::SUPPORTED))->get();
            foreach ($removed as $role) {
                $role->admins()->detach();
                $role->permissions()->detach();
                $role->delete();
            }
        });
    }

    private function permissions(array $slugs): array
    {
        return array_map(fn ($slug) => Permission::firstOrCreate(
            ['slug' => $slug], ['name' => ucwords(str_replace('-', ' ', $slug))]
        )->id, $slugs);
    }
}
