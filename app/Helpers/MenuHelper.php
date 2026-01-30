<?php

namespace App\Helpers;

use App\Models\Menu;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MenuHelper
{
    /**
     * Get user accessible menus (WITH CACHE & BETTER DEBUGGING)
     */
    public static function getUserMenus(): Collection
    {
        $user = auth()->user();

        if (!$user) {
            Log::warning('No authenticated user');
            return collect([]);
        }

        // Disable cache for debugging
        // $roleIds = $user->roles->pluck('id')->sort()->implode(',');
        // $cacheKey = 'user_menus_' . $user->id . '_' . md5($roleIds);
        // $cacheDuration = 3600;

        // return Cache::remember($cacheKey, $cacheDuration, function () use ($user) {
            Log::info('Loading menus from database', [
                'user_id' => $user->id,
                'user_email' => $user->email,
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
            $userPermissions = $user->getAllPermissions();
            $userPermissionIds = $userPermissions->pluck('id')->toArray();
            $userPermissionNames = $userPermissions->pluck('name')->toArray();

            Log::info('User permissions', [
                'count' => count($userPermissionIds),
                'ids' => $userPermissionIds,
                'names' => $userPermissionNames
            ]);

            if (empty($userPermissionIds)) {
                Log::warning('User has no permissions', [
                    'user_id' => $user->id,
                    'roles' => $user->getRoleNames()->toArray()
                ]);
                return collect([]);
            }

            // Get ALL root menus first to debug
            $allMenus = Menu::with('permissions')->whereNull('parent_id')->where('is_active', true)->get();

            Log::info('All root menus', [
                'count' => $allMenus->count(),
                'menus' => $allMenus->map(function($menu) {
                    return [
                        'name' => $menu->name,
                        'permissions_count' => $menu->permissions->count(),
                        'permission_ids' => $menu->permissions->pluck('id')->toArray(),
                        'permission_names' => $menu->permissions->pluck('name')->toArray(),
                    ];
                })->toArray()
            ]);

            // Get root menus with permission check
            $menus = Menu::with(['children.children', 'permissions'])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->whereHas('permissions', function ($q) use ($userPermissionIds) {
                    $q->whereIn('permissions.id', $userPermissionIds);
                })
                ->orderBy('order')
                ->get();

            Log::info('Filtered menus for user', [
                'count' => $menus->count(),
                'menus' => $menus->pluck('name')->toArray()
            ]);

            return $menus;
        // });
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
        Cache::flush();
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