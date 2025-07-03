<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer|exists:menus,id',
            'group_id' => 'nullable|integer',
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'nullable|boolean',
            'order' => 'integer',
        ]);

        $link = \App\Models\Link::create($validated);

        return response()->json($link, 201);
    }

    public function update(Request $request) {}

    public function destroy(Link $link)
    {
        $link->delete();
    }
}
