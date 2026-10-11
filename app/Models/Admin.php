<?php

namespace App\Models;

use App\Services\RolePermissions;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'profile_id',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'boolean',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function roles()
    {
        return $this->belongsToMany(
            Role::class,
            'admin_roles'
        );
    }

    public function sites()
    {
        return $this->belongsToMany(
            Site::class,
            'admin_sites'
        );
    }

    public function activityLogs()
    {
        return $this->hasMany(
            AdminActivityLog::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Role / Permission Helpers
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains('slug', $role);
    }

    public function hasAnyRole(array $roles): bool
    {
        $this->loadMissing('roles');

        return $this->roles->contains(
            fn (Role $role) => in_array($role->slug, $roles, true)
        );
    }

    /** Admins have full member operations within their assigned sites. */
    public function isSiteAdmin(): bool
    {
        return $this->hasRole('admin') && ! $this->hasRole('super-admin');
    }

    public function hasPermission(string $permission): bool
    {
        if ($permission === 'change-password') {
            return true;
        }

        $this->loadMissing('roles');
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return $this->roles->contains(fn (Role $role) => in_array(
            $permission, RolePermissions::forRole($role->slug), true
        ));
    }

    public function hasPermissions(array $permissions): bool
    {
        return collect($permissions)->every(fn (string $permission) => $this->hasPermission($permission));
    }

    public function hasAnyPermission(array $permissions): bool
    {
        if ($this->hasRole('super-admin')) {
            return true;
        }

        return collect($permissions)->contains(fn (string $permission) => $this->hasPermission($permission));
    }

    public function hasSiteAccess(int $siteId): bool
    {
        // Super Admin can access every active site.
        if ($this->hasRole('super-admin')) {
            return Site::where('id', $siteId)
                ->where('status', true)
                ->exists();
        }

        return $this->sites()
            ->where('sites.id', $siteId)
            ->where('sites.status', true)
            ->exists();
    }

    public function canRaiseProfileDeleteRequest(): bool
    {
        return $this->hasPermission('raise-delete-request');
    }
}
