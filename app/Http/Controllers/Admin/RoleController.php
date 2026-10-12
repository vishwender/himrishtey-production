<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Services\RolePermissions;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $roles = Role::withCount('admins')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.roles.index', compact(
            'roles',
            'search'
        ));
    }

    public function create()
    {
        abort(403, 'The four system roles are fixed.');
    }

    public function store(Request $request)
    {
        abort(403, 'The four system roles are fixed.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();

        $selectedPermissions = $role->permissions()
            ->pluck('permissions.id')
            ->toArray();

        return view('admin.roles.edit', compact(
            'role',
            'permissions',
            'selectedPermissions'
        ));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless(array_key_exists($role->slug, Role::SUPPORTED), 404);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                Rule::in([Role::SUPPORTED[$role->slug]]),
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::in([$role->slug]),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'permissions' => [
                'nullable',
                'array',
            ],

            'permissions.*' => [
                'integer', 'min:0',
                Rule::exists('permissions', 'id')->whereIn('slug', RolePermissions::forRole($role->slug)),
            ],
        ]);

        $role->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'] ?? null,
        ]);

        $role->permissions()->sync(
            Permission::whereIn('slug', RolePermissions::forRole($role->slug))->pluck('id')->all()
        );

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        abort(403, 'The four system roles cannot be deleted.');
    }
}
