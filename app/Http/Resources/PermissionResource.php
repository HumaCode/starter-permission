<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
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
            'name' => $this->name,
            'guard_name' => $this->guard_name,

            // Split permission name untuk display
            // Contoh: "read.user" -> action: "read", resource: "user"
            'action' => $this->getPermissionAction(),
            'resource' => $this->getPermissionResource(),

            // Human readable label
            'label' => $this->getHumanReadableLabel(),

            'created_at' => $this->when(
                $request->has('with_timestamps'),
                fn() => $this->created_at?->format('Y-m-d H:i:s')
            ),
        ];
    }

    /**
     * Get permission action (read, create, update, delete)
     */
    protected function getPermissionAction(): ?string
    {
        if (str_contains($this->name, '.')) {
            return explode('.', $this->name)[0];
        }
        return null;
    }

    /**
     * Get permission resource (user, product, order, dll)
     */
    protected function getPermissionResource(): ?string
    {
        if (str_contains($this->name, '.')) {
            return explode('.', $this->name)[1] ?? null;
        }
        return $this->name;
    }

    /**
     * Get human readable label
     */
    protected function getHumanReadableLabel(): string
    {
        $actions = [
            'read' => 'Lihat',
            'create' => 'Tambah',
            'update' => 'Ubah',
            'delete' => 'Hapus',
            'deleteAny' => 'Hapus Banyak',
            'export' => 'Ekspor',
            'import' => 'Impor',
            'restore' => 'Pulihkan',
            'forceDelete' => 'Hapus Permanen',
        ];

        $resources = [
            'user'          => 'Pengguna',
            'role'          => 'Role',
            'permission'    => 'Permission',
            'dashboard'     => 'Dashboard',
            'setting'       => 'Pengaturan',
        ];

        if (str_contains($this->name, '.')) {
            [$action, $resource] = explode('.', $this->name);
            $actionLabel = $actions[$action] ?? ucfirst($action);
            $resourceLabel = $resources[$resource] ?? ucfirst($resource);

            return "{$actionLabel} {$resourceLabel}";
        }

        return ucfirst($this->name);
    }
}
