<?php

namespace App\Http\Controllers;

use App\Concerns\HasHydration;
use App\Models\BlockType;
use App\Models\Collection;
use App\Models\CollectionType;
use App\Models\Page;
use Inertia\Inertia;

class ViewController extends Controller
{
    use HasHydration;

    /**
     * Display home page.
     */
    public function index()
    {
        $page = Page::where('is_home', true)->firstOrFail();

        return $this->renderPage($page, 'Page/Index');
    }

    /**
     * Display page.
     */
    public function page(string $slug)
    {
        $page = Page::where([
            ['slug', $slug],
            ['is_home', false],
        ])->firstOrFail();

        return $this->renderPage($page, 'Page/Index');
    }

    /**
     * Display collection.
     */
    public function collection(string $collection, string $slug)
    {
        $collectionType = CollectionType::where('type', $collection)->firstOrFail();

        $collectionPage = Collection::where([
            ['slug', $slug],
            ['collection_type_id', $collectionType->id],
        ])->firstOrFail();

        if ($collectionPage->status === 'draft') {
            abort(404);
        }

        $this->hydrateCollection($collectionPage);

        return Inertia::render('Collection/Collection', compact('collectionPage'));
    }

    public function navigation()
    {
        $blockTypes = BlockType::all();
        $collectionTypes = CollectionType::all();

        return response()->json([
            'blockTypes' => $blockTypes->only('type', 'name'),
            'collectionTypes' => $collectionTypes,
        ]);
    }

    public function renderPage(Page $page, string $view)
    {
        if ($page->status === 'draft') {
            abort(404);
        }

        $relations = ['blocks', 'forms'];

        if ($page->is_archive) {
            $relations[] = 'collections';
        }

        $page->load($relations);

        $this->hydratePage($page);

        return Inertia::render($view, compact('page'));
    }
}
