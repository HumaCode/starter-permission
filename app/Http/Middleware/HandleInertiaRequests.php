<?php

namespace App\Http\Middleware;

use App\Helpers\MenuHelper;
use App\Http\Resources\MenuResource;
use App\Http\Resources\UserSingleResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        $menus = [];

        if ($request->user()) {
            try {
                $menusCollection = MenuHelper::getUserMenus();
                $menus = MenuResource::collection($menusCollection)->resolve();

                Log::info('Menus shared to frontend', [
                    'url' => $request->url(),
                    'count' => count($menus),
                    'is_array' => is_array($menus)
                ]);
            } catch (\Exception $e) {
                Log::error('Error sharing menus', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }
        }

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user() ? new UserSingleResource($request->user()) : null,
            ],
            'menus' => $menus, // ← Always array
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
}