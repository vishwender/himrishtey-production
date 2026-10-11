<?php

namespace App\Services;

use App\Models\Admin;
use App\Models\SiteMember;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RelationshipManagerAccess
{
    public function admin(): ?Admin
    {
        $admin = Auth::guard('admin')->user();

        return $admin instanceof Admin ? $admin : null;
    }

    public function isRestricted(): bool
    {
        $admin = $this->admin();

        if (! $admin || $admin->hasRole('super-admin') || $admin->isSiteAdmin()) {
            return false;
        }

        return $admin->hasRole('relationship-manager');
    }

    public function ensureRouteAccess(Request $request): void
    {
        if (! $this->isRestricted()) {
            return;
        }

        abort_unless($request->routeIs(
            'admin.members.*', 'admin.activities.*', 'admin.rotations.*',
            'admin.delete-profile-requests.*', 'admin.site.select', 'admin.site.switch', 'admin.logout', 'admin.settings.password.*'
        ) && ! $request->routeIs('admin.members.membership-expiry'), 403,
            'Relationship managers can only access Manage Members.');
    }

    /** @return list<string> */
    public function assignedIdentifiers(): array
    {
        $admin = $this->admin();

        if (! $admin) {
            return [];
        }

        return array_values(array_unique(array_filter([
            trim((string) $admin->name),
            trim((string) $admin->profile_id),
        ])));
    }

    /**
     * @template TBuilder of EloquentBuilder|QueryBuilder
     *
     * @param  TBuilder  $query
     * @return TBuilder
     */
    public function scope(
        EloquentBuilder|QueryBuilder $query,
        string $column = 'relationship_manager'
    ) {
        if ($this->isRestricted()) {
            $query->whereIn($column, $this->assignedIdentifiers());
        }

        return $query;
    }

    public function canAccessMember(int $memberId): bool
    {
        if (! $this->isRestricted()) {
            return true;
        }

        return SiteMember::withoutGlobalScopes()
            ->whereKey($memberId)
            ->exists();
    }
}
