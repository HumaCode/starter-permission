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
            'id'        => $this->id,
            'parent_id' => $this->parent_id,
            'level'     => $this->level,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'route'     => $this->route,
            'icon'      => $this->icon,
            'order'     => $this->order,
            'is_active' => $this->is_active,

            // Metadata (badge, description, dll)
            'metadata' => $this->metadata,

            // Full path breadcrumb (optional, useful untuk debugging)
            'full_path' => $this->when(
                $request->has('with_path'),
                fn() => $this->getFullPath()
            ),

            // Has children indicator
            'has_children' => $this->children()->exists(),

            // Children (recursive)
            'children' => MenuResource::collection($this->whenLoaded('children')),

            // Permissions attached to this menu
            'permissions' => PermissionResource::collection($this->whenLoaded('permissions')),

            // Timestamps (optional, bisa di-exclude untuk production)
            'created_at' => $this->when(
                $request->has('with_timestamps'),
                fn() => $this->created_at?->format('Y-m-d H:i:s')
            ),
            'updated_at' => $this->when(
                $request->has('with_timestamps'),
                fn() => $this->updated_at?->format('Y-m-d H:i:s')
            ),
        ];
    }
}
