<?php

namespace App\Http\Middleware;

use App\Http\Resources\MenuResource;
use App\Http\Resources\UserSingleResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? new UserSingleResource($request->user()) : null,
            ],
            'menus' => fn() => $request->user() ? $this->getUserMenus($request->user()) : [],
            'flash_message' => fn() => [
                'type' => $request->session()->get('type'),
                'message' => $request->session()->get('message'),
            ],
            'ziggy' => fn() => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }

    /**
     * Get user accessible menus based on permissions
     *
     * @param \App\Models\User $user
     * @return array
     */
    protected function getUserMenus($user): array
    {
        // Administrator gets all menus
        if ($user->hasRole('administrator')) {
            $menus = Menu::with(['children.children', 'permissions'])
                ->whereNull('parent_id')
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

            return MenuResource::collection($menus)->resolve();
        }

        // Regular user - filter by permissions
        $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();

        if (empty($userPermissions)) {
            return [];
        }

        $menus = Menu::with(['children.children', 'permissions'])
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->where(function ($query) use ($userPermissions) {
                // Check if menu itself has permission
                $query->whereHas('permissions', function ($q) use ($userPermissions) {
                    $q->whereIn('permissions.id', $userPermissions);
                })
                    // Or if any of its children has permission
                    ->orWhereHas('children', function ($q) use ($userPermissions) {
                        $q->where('is_active', true)
                            ->where(function ($subQuery) use ($userPermissions) {
                                $subQuery->whereHas('permissions', function ($pq) use ($userPermissions) {
                                    $pq->whereIn('permissions.id', $userPermissions);
                                })
                                    // Or if any of its grandchildren has permission
                                    ->orWhereHas('children', function ($cq) use ($userPermissions) {
                                        $cq->where('is_active', true)
                                            ->whereHas('permissions', function ($pq) use ($userPermissions) {
                                                $pq->whereIn('permissions.id', $userPermissions);
                                            });
                                    });
                            });
                    });
            })
            ->orderBy('order')
            ->get();

        // Filter children based on permissions
        $menus = $menus->map(function ($menu) use ($userPermissions) {
            return $this->filterMenuChildren($menu, $userPermissions);
        })->filter();

        return MenuResource::collection($menus)->resolve();
    }

    /**
     * Recursively filter menu children based on user permissions
     *
     * @param \App\Models\Menu $menu
     * @param array $userPermissions
     * @return \App\Models\Menu|null
     */
    protected function filterMenuChildren($menu, $userPermissions)
    {
        // If menu has children, filter them
        if ($menu->children && $menu->children->count() > 0) {
            $filteredChildren = $menu->children->map(function ($child) use ($userPermissions) {
                return $this->filterMenuChildren($child, $userPermissions);
            })->filter();

            // If no children remain after filtering, check if menu itself has permission
            if ($filteredChildren->isEmpty()) {
                $menuPermissionIds = $menu->permissions->pluck('id')->toArray();
                $hasAccess = !empty(array_intersect($menuPermissionIds, $userPermissions));

                if (!$hasAccess) {
                    return null;
                }
            }

            // Update children with filtered list
            $menu->setRelation('children', $filteredChildren);
        } else {
            // No children - check if menu itself has permission
            $menuPermissionIds = $menu->permissions->pluck('id')->toArray();
            $hasAccess = !empty(array_intersect($menuPermissionIds, $userPermissions));

            if (!$hasAccess) {
                return null;
            }
        }

        return $menu;
    }
}
