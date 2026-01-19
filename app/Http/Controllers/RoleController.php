<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Shield\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Inertia\Response;

class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    public function index(): Response
    {
        $roles = Role::query()
            ->select(['id', 'name', 'guard_name', 'created_at'])
            ->filter(request()->only(['search']))
            ->sorting(request()->only(['field', 'direction']))
            ->paginate(request()->load ?? 10);

        return inertia('Roles/Index', [
            'pageSettings' => fn() => [
                'title' => 'Daftar Role',
                'subtitle' => 'Role untuk membedakan hak akses antar user.',
                'banner' => [
                    'title' => 'Role',
                    'subtitle' => 'untuk membedakan hak akases user, sehingga tidak adanya kerancuan dalam menghakses halaman.'
                ],
            ],
            'roles' => fn() => RoleResource::collection($roles)->additional([
                'meta' => [
                    'has_pages' => $roles->hasPages()
                ]
            ]),
            'state' =>  fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
            'items' => fn() => [
                ['label' => 'Role Permission', 'href' => route('dashboard')],
                ['label' => 'Role'],
            ],
            'year' => fn() => now()->year(),
        ]);
    }
}
