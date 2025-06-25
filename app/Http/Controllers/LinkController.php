<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;

class LinkController extends Controller
{
    public function store(Request $request)
    {
        // dd($request->all());

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|string|max:255',
            'target' => 'nullable|boolean',
        ]);

        $link = \App\Models\Link::create($validated);

        return response()->json($link, 201);
    }

    public function destroy(Link $link)
    {
        $link->delete();
    }
}
