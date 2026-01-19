<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'parent_id' => $this->parent_id,
            'level' => $this->level,
            'level_label' => $this->getLevelLabel(), // ← Tambahkan ini
            'level_badge_color' => $this->getLevelBadgeColor(), // ← Tambahkan ini
            'name' => $this->name,
            'slug' => $this->slug,
            'route' => $this->route,
            'icon' => $this->icon,
            'order' => $this->order,
            'is_active' => $this->is_active,
            'metadata' => $this->metadata,

            // Parent info
            'parent' => $this->when(
                $this->relationLoaded('parent'),
                fn() => $this->parent ? [
                    'id' => $this->parent->id,
                    'name' => $this->parent->name,
                ] : null
            ),

            // Full path breadcrumb
            'full_path' => $this->when(
                $request->has('with_path'),
                fn() => $this->getFullPath()
            ),

            // Has children indicator
            'has_children' => $this->children()->exists(),

            // Children count
            'children_count' => $this->when(
                $request->has('with_counts'),
                fn() => $this->children()->count()
            ),

            // Children (recursive)
            'children' => MenuResource::collection($this->whenLoaded('children')),

            // Permissions
            'permissions' => $this->when(
                $this->relationLoaded('permissions'),
                fn() => PermissionResource::collection($this->permissions)
            ),

            // Permissions count
            'permissions_count' => $this->when(
                $this->relationLoaded('permissions'),
                fn() => $this->permissions->count()
            ),

            // Check if current user can access this menu
            'can_access' => $this->when(
                auth()->check(),
                fn() => $this->canUserAccess(auth()->user())
            ),

            // Formatted date
            'created_at' => $this->created_at?->format('d M Y H:i'),
            'created_at_human' => $this->created_at?->diffForHumans(),
        ];
    }

    /**
     * Check if user can access this menu
     */
    protected function canUserAccess($user): bool
    {
        if ($user->hasRole('administrator')) {
            return true;
        }

        if (!$this->relationLoaded('permissions')) {
            return false;
        }

        $menuPermissions = $this->permissions->pluck('id')->toArray();
        $userPermissions = $user->getAllPermissions()->pluck('id')->toArray();

        return !empty(array_intersect($menuPermissions, $userPermissions));
    }
}
