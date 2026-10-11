<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display permissions.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $permissions = Permission::with('roles')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.permissions.index', compact(
            'permissions',
            'search'
        ));
    }

    /**
     * Store permission.
     */
    public function store(Request $request)
    {
        abort(403, 'Permissions are defined by the four system roles.');
    }

    /**
     * Update permission.
     */
    public function update(Request $request, Permission $permission)
    {
        abort(403, 'Permissions are defined by the four system roles.');
    }

    /**
     * Delete permission.
     */
    public function destroy(Permission $permission)
    {
        abort(403, 'Permissions are defined by the four system roles.');
    }
}
