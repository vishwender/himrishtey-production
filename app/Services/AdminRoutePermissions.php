<?php

namespace App\Services;

use Illuminate\Http\Request;

class AdminRoutePermissions
{
    public function required(Request $request): ?string
    {
        if ($request->routeIs('admin.site.select', 'admin.site.switch', 'admin.logout', 'admin.settings.password.*')) {
            return null;
        }

        $rules = [
            'admin.settings.content.*' => 'manage-site-content',
            'admin.dashboard' => 'view-dashboard',
            'admin.members.membership-expiry' => 'view-membership-expiry',
            'admin.members.print' => 'print-members',
            'admin.members.ban.update' => 'ban-members',
            'admin.members.new.assign-staff' => 'assign-members',
            'admin.members.relationship-manager.update' => 'assign-members',
            'admin.members.membership.change' => 'change-member-membership',
            'admin.members.remarks.update' => 'edit-member-remarks',
            'admin.members.create' => 'create-members',
            'admin.members.store' => 'create-members',
            'admin.members.edit' => 'edit-member',
            'admin.members.update' => 'edit-member',
            'admin.members.toggle-status' => 'manage-member-status',
            'admin.members.activation.*' => 'manage-member-status',
            'admin.members.toggle-trusted' => 'manage-member-trusted',
            'admin.members.toggle-visibility' => 'manage-member-visibility',
            'admin.members.toggle-promoted' => 'manage-member-promoted',
            'admin.members.photos.*' => 'manage-member-photos',
            'admin.members.advanced-search*' => 'advanced-search-members',
            'admin.members.rotation.*' => 'add-rotations',
            'admin.members.delete-request' => 'raise-delete-request',
            'admin.members.delete-requests.*' => 'view-delete-profile-request',
            'admin.delete-profile-requests.accept' => 'approve-profile-delete-request',
            'admin.delete-profile-requests.reject' => 'reject-profile-delete-request',
            'admin.delete-profile-requests.bulk-action' => 'bulk-profile-delete-requests',
            'admin.delete-profile-requests.*' => 'view-delete-profile-request',
            'admin.members.*' => 'view-members',
            'admin.activities.*' => 'view-member-activity',
            'admin.rotations.complete' => 'complete-rotations',
            'admin.rotations.destroy' => 'delete-rotations',
            'admin.rotations.*' => 'view-own-rotations',
            'admin.blog-posts.*' => 'view-content-management',
            'admin.success-stories.*' => 'view-content-management',
            'admin.pages.*' => 'view-content-management',
            'admin.payments.*' => 'view-payments',
            'admin.site-stats.*' => 'view-site-stats',
            'admin.staff.activity' => 'view-staff-activity',
        ];

        foreach ($rules as $pattern => $permission) {
            if ($request->routeIs($pattern)) {
                return $permission;
            }
        }

        return 'manage-site-administration';
    }

    public function authorize(Request $request): void
    {
        $permission = $this->required($request);
        if ($permission !== null) {
            abort_unless(auth('admin')->user()?->hasPermission($permission), 403,
                'You do not have permission to access this page.');
        }
    }
}
