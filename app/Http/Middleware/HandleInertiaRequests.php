<?php

namespace App\Http\Middleware;

use App\Models\BlockType;
use App\Models\CollectionType;
use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'settings' => Setting::singleton(),
            'auth' => [
                'user' => $request->user(),
                'roles' => $request->user() ? $request->user()->getRoleNames()->toArray() : [],
                'permissions' => $request->user() ? $request->user()->getAllPermissions()->pluck('name')->toArray() : [],
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'fields' => config('fields.type'),
            'collectionTypes' => CollectionType::all()->pluck('id', 'name'),
            'navigation' => [
                'blockTypes' => BlockType::all()->map(fn ($blockType) => ['name' => $blockType->name, 'type' => $blockType->type]),
                'collectionTypes' => CollectionType::all()->map(fn ($collectionType) => ['name' => $collectionType->name, 'type' => $collectionType->type]),
            ],
            'menus' => fn () => \App\Models\Menu::where('active', true)
                ->with(['links', 'groups.links'])
                ->get()
                ->map(function ($menu) {
                    // cache entries pour éviter lazy load
                    $entries = $menu->entries;

                    // masquer groups et links
                    unset($menu->links);
                    unset($menu->groups);

                    // optionnel : exposer entries en tant qu'attribut visible
                    $menu->entries = $entries;

                    return $menu;
                }),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('info'),
            ],
        ];
    }
}
