<?php

namespace App\Http\Controllers;

use App\Http\Resources\MenuResource;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;

class MenuController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index(): Response
    {
        $menus = Menu::query()
            ->with(['parent:id,name', 'permissions:id,name'])
            ->select(['id', 'parent_id', 'level', 'name', 'slug', 'route', 'icon', 'order', 'is_active', 'created_at'])
            ->filter(request()->only(['search', 'level', 'parent_id', 'is_active']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Menus/Index', [
            'pageSettings' => fn() => [
                'title' => 'Daftar Menu',
                'subtitle' => 'Kelola menu navigasi dan hak akses sistem.',
                'banner' => [
                    'title' => 'Menu Management',
                    'subtitle' => 'Atur struktur menu dan permission untuk mengontrol akses halaman setiap user.'
                ],
            ],
            'menus' => fn() => MenuResource::collection($menus)->additional([
                'meta' => [
                    'has_pages' => $menus->hasPages()
                ]
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'level' => request()->level ?? '',
                'parent_id' => request()->parent_id ?? '',
                'is_active' => request()->is_active ?? '',
                'load' => request()->load ?? 10,
            ],
            'filters' => fn() => [
                'levels' => [
                    ['value' => '', 'label' => 'Semua Level'],
                    ['value' => 'menu', 'label' => 'Menu'],
                    ['value' => 'submenu', 'label' => 'Submenu'],
                    ['value' => 'childmenu', 'label' => 'Child Menu'],
                ],
                'parents' => Menu::whereNull('parent_id')
                    ->select('id', 'name')
                    ->orderBy('name')
                    ->get()
                    ->map(fn($menu) => [
                        'value' => $menu->id,
                        'label' => $menu->name
                    ])
                    ->prepend(['value' => '', 'label' => 'Semua Parent']),
                'statuses' => [
                    ['value' => '', 'label' => 'Semua Status'],
                    ['value' => '1', 'label' => 'Aktif'],
                    ['value' => '0', 'label' => 'Nonaktif'],
                ],
            ],
            'items' => fn() => [
                ['label' => 'Role Permission', 'href' => route('dashboard')],
                ['label' => 'Menu'],
            ],
            'year' => fn() => now()->year(),
        ]);
    }
}
