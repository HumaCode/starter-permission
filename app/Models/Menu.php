<?php

namespace App\Models;

use App\Models\Shield\Permission;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Menu extends Model
{
    use HasUuids;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'parent_id',
        'level',
        'name',
        'slug',
        'route',
        'icon',
        'order',
        'is_active',
        'metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }

            // Auto set level based on parent
            if ($model->parent_id) {
                $parent = static::find($model->parent_id);
                if ($parent) {
                    $model->level = match($parent->level) {
                        'menu' => 'submenu',
                        'submenu' => 'childmenu',
                        default => 'childmenu'
                    };
                }
            }
        });
    }

    // Relationships
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'menu_permission',
            'menu_id',
            'permission_id'
        );
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeRootMenus($query)
    {
        return $query->whereNull('parent_id')
            ->where('is_active', true)
            ->orderBy('order');
    }

    public function scopeLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    // Helpers
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    public function getFullPath(): string
    {
        $path = collect([$this->name]);
        $parent = $this->parent;

        while ($parent) {
            $path->prepend($parent->name);
            $parent = $parent->parent;
        }

        return $path->implode(' > ');
    }
}
