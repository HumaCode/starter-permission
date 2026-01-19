<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Collection;

class MenuHelper
{
    /**
     * Get user accessible menus
     */
    public static function getUserMenus(): Collection
    {
        $user = auth()->user();

        // Administrator bypass all
        if ($user->hasRole('administrator')) {
            return Menu::with('children.children') // nested 3 level
                ->rootMenus()
                ->get();
        }

        // Get user permissions
        $userPermissions = $user->getAllPermissions()->pluck('id');

        // Get menus with permission check (recursive)
        return self::getMenusWithPermissions($userPermissions);
    }

    /**
     * Recursive get menus with permission filtering
     */
    protected static function getMenusWithPermissions($permissionIds, $parentId = null): Collection
    {
        return Menu::with(['children' => function ($query) use ($permissionIds) {
                $query->where(function ($q) use ($permissionIds) {
                    $q->whereHas('permissions', function ($pq) use ($permissionIds) {
                        $pq->whereIn('permissions.id', $permissionIds);
                    })->orWhereHas('children.permissions', function ($pq) use ($permissionIds) {
                        $pq->whereIn('permissions.id', $permissionIds);
                    });
                });
            }])
            ->where('parent_id', $parentId)
            ->where('is_active', true)
            ->where(function ($query) use ($permissionIds) {
                $query->whereHas('permissions', function ($q) use ($permissionIds) {
                    $q->whereIn('permissions.id', $permissionIds);
                })->orWhereHas('children.permissions', function ($q) use ($permissionIds) {
                    $q->whereIn('permissions.id', $permissionIds);
                });
            })
            ->orderBy('order')
            ->get();
    }

    /**
     * Check if user can access menu
     */
    public static function canAccessMenu(Menu $menu): bool
    {
        $user = auth()->user();

        if ($user->hasRole('administrator')) {
            return true;
        }

        $menuPermissions = $menu->permissions->pluck('id');
        $userPermissions = $user->getAllPermissions()->pluck('id');

        return $menuPermissions->intersect($userPermissions)->isNotEmpty();
    }
}