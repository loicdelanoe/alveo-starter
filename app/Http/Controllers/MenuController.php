<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $menus = Menu::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%'.$request->search.'%');
        })->orderBy('created_at', 'desc')->paginate(8);

        // $menus->load('pages');

        return Inertia::render('Admin/Menu/Index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $pages = Page::where('status', 'published')->select(['id', 'title', 'slug'])
            ->get();

        $navigableTypes = [
            'page' => 'page',
            'link' => 'link',
        ];

        return Inertia::render('Admin/Menu/Create', compact('pages', 'navigableTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $validated = $request->validated();

        $menu = Menu::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'active' => $validated['active'],
        ]);

        return to_route('admin.menu.show', $menu)->with('success', 'Menu created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        $menu->load(['navigations.navigable']);

        $pages = Page::where('status', 'published')
            ->get();

        return Inertia::render('Admin/Menu/Show', compact('menu', 'pages'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $validated = $request->validated();

        $menu->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'active' => $validated['active'],
        ]);

        // Replace existing navigations
        $menu->navigations()->delete(); // or ->forceDelete() if soft deleted

        foreach ($validated['navigations'] as $index => $navigation) {
            $menu->navigations()->create([
                'parent_id' => $navigation['parent_id'] ?? null,
                'navigable_type' => $navigation['navigable_type'],
                'navigable_id' => $navigation['navigable_id'],
                'target' => $navigation['target'] ?? null,
                'order' => $index,
            ]);
        }

        return to_route('admin.menu.show', $menu)->with('success', 'Menu updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return to_route('admin.menu.index')->with('success', 'Menu deleted successfully.');
    }
}
