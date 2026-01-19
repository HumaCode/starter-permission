<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

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

    /**
     * Scope untuk filter
     */
    public function scopeFilter(Builder $query, array $filters): void
    {
        // Search by name, slug, or route
        $query->when($filters['search'] ?? null, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'REGEXP', $search)
                  ->orWhere('slug', 'REGEXP', $search)
                  ->orWhere('route', 'REGEXP', $search);
            });
        });

        // Filter by level (menu, submenu, childmenu)
        $query->when($filters['level'] ?? null, function ($query, $level) {
            $query->where('level', $level);
        });

        // Filter by parent_id
        $query->when(isset($filters['parent_id']), function ($query) use ($filters) {
            if ($filters['parent_id'] === 'root' || $filters['parent_id'] === '') {
                // Show all or root menus only based on your logic
                if ($filters['parent_id'] === 'root') {
                    $query->whereNull('parent_id');
                }
            } else {
                $query->where('parent_id', $filters['parent_id']);
            }
        });

        // Filter by is_active status
        $query->when(isset($filters['is_active']) && $filters['is_active'] !== '', function ($query) use ($filters) {
            $query->where('is_active', (bool) $filters['is_active']);
        });

        // Filter by icon (has icon or not)
        $query->when(isset($filters['has_icon']), function ($query) use ($filters) {
            if ($filters['has_icon']) {
                $query->whereNotNull('icon');
            } else {
                $query->whereNull('icon');
            }
        });

        // Filter by route (has route or not)
        $query->when(isset($filters['has_route']), function ($query) use ($filters) {
            if ($filters['has_route']) {
                $query->whereNotNull('route');
            } else {
                $query->whereNull('route');
            }
        });

        // Filter menus that have permissions
        $query->when(isset($filters['has_permissions']), function ($query) use ($filters) {
            if ($filters['has_permissions']) {
                $query->whereHas('permissions');
            } else {
                $query->whereDoesntHave('permissions');
            }
        });

        // Filter menus that have children
        $query->when(isset($filters['has_children']), function ($query) use ($filters) {
            if ($filters['has_children']) {
                $query->whereHas('children');
            } else {
                $query->whereDoesntHave('children');
            }
        });
    }

    /**
     * Scope untuk sorting
     */
    public function scopeSorting(Builder $query, array $sorts): void
    {
        $query->when($sorts['field'] ?? null && $sorts['direction'] ?? null, function ($query) use ($sorts) {
            $allowedFields = ['name', 'slug', 'route', 'order', 'level', 'is_active', 'created_at', 'updated_at'];

            $field = $sorts['field'];
            $direction = strtolower($sorts['direction']);

            // Validate field and direction
            if (in_array($field, $allowedFields) && in_array($direction, ['asc', 'desc'])) {
                $query->orderBy($field, $direction);
            } else {
                // Default sorting
                $query->orderBy('order', 'asc')->orderBy('name', 'asc');
            }
        }, function ($query) {
            // Default sorting when no sort parameters provided
            $query->orderBy('order', 'asc')->orderBy('name', 'asc');
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

    /**
     * Get level badge color
     */
    public function getLevelBadgeColor(): string
    {
        return match($this->level) {
            'menu' => 'blue',
            'submenu' => 'green',
            'childmenu' => 'purple',
            default => 'gray'
        };
    }

    /**
     * Get level label
     */
    public function getLevelLabel(): string
    {
        return match($this->level) {
            'menu' => 'Menu',
            'submenu' => 'Submenu',
            'childmenu' => 'Child Menu',
            default => ucfirst($this->level)
        };
    }
}