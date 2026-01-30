<?php

namespace App\Models;

use App\Models\Shield\Permission;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuPermission extends Model
{
    protected $table = 'menu_permission';

    protected $fillable = [
        'menu_id',
        'permission_id',
    ];

    /**
     * Get the menu for this permission.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    /**
     * Get the permission for this menu.
     */
    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id');
    }
}
