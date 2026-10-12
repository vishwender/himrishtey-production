@extends('admin.layout')

@section('title', 'Permissions')
@section('page-title', 'Permissions')
@section('content')
<div class="container-fluid py-4">
    <h1 class="h3 mb-1">Permissions</h1>
    <p class="text-muted">Permissions are fixed by role. Change Password is available to every signed-in staff user.</p>
    <form method="GET" class="mb-3">
        <input name="search" value="{{ $search }}" placeholder="Search permissions" class="form-control">
    </form>
    <div class="card"><div class="table-responsive">
        <table class="table mb-0"><thead><tr><th>Permission</th><th>Slug</th><th>Roles</th></tr></thead><tbody>
        @forelse($permissions as $permission)
            <tr><td>{{ $permission->name }}</td><td><code>{{ $permission->slug }}</code></td><td>{{ $permission->roles->pluck('name')->join(', ') }}</td></tr>
        @empty
            <tr><td colspan="3">No permissions found.</td></tr>
        @endforelse
        </tbody></table>
    </div></div>
    <div class="mt-3">{{ $permissions->links() }}</div>
</div>
@endsection
