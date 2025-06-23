<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateSettingRequest;
use App\Models\Setting;
use App\Service\FileService;
use Inertia\Inertia;

class SettingController extends Controller
{
    protected Setting $settings;

    public function __construct(Setting $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Setting/Index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UpdateSettingRequest $request)
    {
        $validated = $request->validated();

        if ($request->hasFile('favicon')) {
            $favicon = (new FileService)->uploadFavicon($validated['favicon']);

            $validated['favicon'] = $favicon->path;
        } else {
            unset($validated['favicon']);
        }

        $settings = Setting::singleton();
        $settings->update($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(UpdateSettingRequest $request) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
