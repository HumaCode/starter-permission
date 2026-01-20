<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MenuHelper
{
    /**
     * Get user accessible menus (WITH CACHE)
     */
    public static function getUserMenus(): Collection
    {
        $user = auth()->user();

        if (!$user) {
            Log::warning('No authenticated user');
            return collect([]);
        }

        // Cache key based on user ID and roles
        $roleIds = $user->roles->pluck('id')->sort()->implode(',');
        $cacheKey = 'user_menus_' . $user->id . '_' . md5($roleIds);
        $cacheDuration = 3600; // 1 hour

        return Cache::remember($cacheKey, $cacheDuration, function () use ($user) {
            Log::info('Loading menus from database', [
                'user_id' => $user->id,
                'roles' => $user->getRoleNames()->toArray()
            ]);

            // Administrator bypass all
            if ($user->hasRole('administrator')) {
                $menus = Menu::with(['children.children', 'permissions'])
                    ->whereNull('parent_id')
                    ->where('is_active', true)
                    ->orderBy('order')
                    ->get();

                Log::info('Administrator menus loaded', [
                    'count' => $menus->count(),
                    'menus' => $menus->pluck('name')->toArray()
                ]);

                return $menus;
            }

            // Get user permissions
            $userPermissions = $user->getAllPermissions()->pluck('id');

            if ($userPermissions->isEmpty()) {
                Log::warning('User has no permissions', ['user_id' => $user->id]);
                return collect([]);
            }

            Log::info('User permissions loaded', [
                'user_id' => $user->id,
                'permissions_count' => $userPermissions->count()
            ]);

            // Get menus with permission check (recursive)
            $menus = self::getMenusWithPermissions($userPermissions);

            Log::info('User menus loaded', [
                'count' => $menus->count(),
                'menus' => $menus->pluck('name')->toArray()
            ]);

            return $menus;
        });
    }

    /**
     * Recursive get menus with permission filtering
     */
    protected static function getMenusWithPermissions($permissionIds, $parentId = null): Collection
    {
        return Menu::with(['children.children', 'permissions'])
            ->where('parent_id', $parentId)
            ->where('is_active', true)
            ->where(function ($query) use ($permissionIds) {
                $query->whereHas('permissions', function ($q) use ($permissionIds) {
                    $q->whereIn('permissions.id', $permissionIds);
                })->orWhereHas('children.permissions', function ($q) use ($permissionIds) {
                    $q->whereIn('permissions.id', $permissionIds);
                })->orWhereHas('children.children.permissions', function ($q) use ($permissionIds) {
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

        if (!$user) {
            return false;
        }

        if ($user->hasRole('administrator')) {
            return true;
        }

        $menuPermissions = $menu->permissions->pluck('id');
        $userPermissions = $user->getAllPermissions()->pluck('id');

        return $menuPermissions->intersect($userPermissions)->isNotEmpty();
    }

    /**
     * Clear menu cache for specific user
     */
    public static function clearCacheForUser($userId): void
    {
        $user = \App\Models\User::find($userId);

        if (!$user) {
            return;
        }

        $roleIds = $user->roles->pluck('id')->sort()->implode(',');
        $cacheKey = 'user_menus_' . $user->id . '_' . md5($roleIds);
        Cache::forget($cacheKey);

        Log::info('Menu cache cleared for user', ['user_id' => $userId]);
    }

    /**
     * Clear menu cache for all users
     */
    public static function clearAllCache(): void
    {
        // Clear all cache keys starting with 'user_menus_'
        Cache::flush(); // Or use specific pattern if using Redis

        Log::info('All menu caches cleared');
    }

    /**
     * Clear menu cache for users with specific role
     */
    public static function clearCacheForRole($roleName): void
    {
        $users = \App\Models\User::role($roleName)->get();

        foreach ($users as $user) {
            self::clearCacheForUser($user->id);
        }

        Log::info('Menu cache cleared for role', ['role' => $roleName]);
    }
}