<?php

namespace App\Services;

class RolePermissions
{
    public const MEMBER_PERMISSIONS = [
        'view-members', 'create-members', 'edit-members', 'edit-member',
        'view-photos', 'manage-member-photos', 'manage-member-status',
        'manage-member-visibility', 'manage-member-trusted', 'manage-member-promoted',
        'advanced-search-members', 'view-own-rotations',
        'create-rotations', 'add-rotations', 'edit-rotations', 'complete-rotations',
        'cancel-rotations', 'delete-rotations', 'raise-delete-request',
        'view-delete-profile-request', 'approve-profile-delete-request',
        'reject-profile-delete-request', 'bulk-profile-delete-requests',
    ];

    public const CONTENT_PERMISSIONS = [
        'manage-site-content', 'view-content-management',
        'add-blogs', 'edit-blogs', 'delete-blogs',
        'edit-pages', 'add-success-stories', 'edit-success-stories', 'delete-success-stories',
    ];

    public static function forRole(string $slug): array
    {
        $common = ['change-password'];

        return match ($slug) {
            'super-admin' => self::all(),
            'admin' => array_merge($common, self::MEMBER_PERMISSIONS, [
                'view-dashboard', 'view-member-activity', 'print-members',
                'ban-members', 'assign-members', 'change-member-membership', 'edit-member-remarks',
                'view-membership-expiry',
            ]),
            'relationship-manager' => array_merge($common, self::MEMBER_PERMISSIONS, [
                'view-member-activity', 'change-member-membership', 'edit-member-remarks',
            ]),
            'content-manager' => array_merge($common, self::CONTENT_PERMISSIONS),
            default => [],
        };
    }

    public static function all(): array
    {
        return array_values(array_unique(array_merge(
            ['change-password', 'view-dashboard', 'view-member-activity', 'print-members',
                'ban-members', 'assign-members', 'change-member-membership', 'edit-member-remarks',
                'view-membership-expiry', 'view-all-rotations', 'view-payments', 'view-site-stats',
                'view-staff-activity', 'manage-site-administration'],
            self::MEMBER_PERMISSIONS, self::CONTENT_PERMISSIONS
        )));
    }
}
