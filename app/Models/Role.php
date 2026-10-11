<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const SUPPORTED = [
        'super-admin' => 'Super Admin',
        'admin' => 'Admin',
        'relationship-manager' => 'Relationship Manager',
        'content-manager' => 'Content Manager',
    ];

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    public function admins()
    {
        return $this->belongsToMany(
            Admin::class,
            'admin_roles'
        );
    }

    public function permissions()
    {
        return $this->belongsToMany(
            Permission::class,
            'role_permissions'
        );
    }
}
