<?php

namespace App\Models\Shield;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as ModelsPermission;

class Permission extends ModelsPermission
{
    use HasUuids;

    protected $fillable = ['name', 'guard_name'];

    public function menus(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_permission', 'permission_id', 'menu_id');
    }
}
