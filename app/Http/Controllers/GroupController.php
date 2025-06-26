<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|integer|exists:menus,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:groups,slug',
            'order' => 'integer',
        ]);

        $group = \App\Models\Group::create($validated);

        return response()->json($group, 201);
    }
}
