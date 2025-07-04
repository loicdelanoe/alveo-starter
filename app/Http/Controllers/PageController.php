<?php

namespace App\Http\Controllers;

use App\Concerns\hasDynamicValidation;
use App\Http\Requests\StorePageRequest;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Block;
use App\Models\BlockType;
use App\Models\Form;
use App\Models\Page;
use App\Service\PageService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    use hasDynamicValidation;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $pages = Page::when($request->search, function ($query) use ($request) {
            $query->where('title', 'like', '%'.$request->search.'%');
        })->orderBy('created_at', 'desc')->paginate(8);

        return Inertia::render('Admin/Page/Index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $blocks = Block::all();
        $blockTypes = BlockType::all();
        $forms = Form::all();

        return Inertia::render('Admin/Page/Create', compact('blocks', 'blockTypes', 'forms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePageRequest $request)
    {
        $validated = $request->validated();

        $page = (new PageService)->storePage($validated);

        return to_route('admin.page.show', $page)->with('success', 'Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        $page->load(['blocks', 'forms']);
        $blocks = Block::all();
        $blockTypes = BlockType::all();
        $forms = Form::all();

        return Inertia::render('Admin/Page/Show', compact('page', 'blocks', 'blockTypes', 'forms'));
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
    public function update(UpdatePageRequest $request, Page $page)
    {
        $validated = $request->validated();

        $page = (new PageService)->updatePage($validated, $page);

        return to_route('admin.page.show', $page)->with('success', 'Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->blocks()->detach();

        $page->delete();

        return to_route('admin.page.index')->with('success', 'Page deleted successfully.');
    }

    /**
     * Display page settings.
     */
    public function settings()
    {
        $pages = Page::all();

        return Inertia::render('Admin/Page/Settings', compact('pages'));
    }

    /**
     * Update page settings.
     */
    public function updateSettings(Request $request)
    {
        // Pass false to the previous home page
        Page::where('is_home', true)->update(['is_home' => false]);

        $newHomePage = Page::where('slug', $request->slug)->firstOrFail();

        $newHomePage->update(['is_home' => true]);

        return to_route('admin.page.settings')->with('success', 'Settings updated successfully.');
    }

    public function getPages()
    {
        return response()->json(Page::all());
    }
}
