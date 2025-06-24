<?php

namespace App\Http\Controllers;

use App\Concerns\hasDynamicValidation;
use App\Http\Requests\StoreCollectionRequest;
use App\Http\Requests\UpdateCollectionRequest;
use App\Models\Collection;
use App\Models\CollectionType;
use Inertia\Inertia;

class CollectionController extends Controller
{
    use hasDynamicValidation;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $collections = Collection::all();
        $collectionTypes = CollectionType::all();

        return Inertia::render('Admin/Collection/Index', compact('collections', 'collectionTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CollectionType $collectionType)
    {
        return Inertia::render('Admin/Collection/Create', compact('collectionType'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCollectionRequest $request, CollectionType $collectionType)
    {
        $validated = $request->validated();

        $this->validateBlock($validated, $collectionType);

        $validated['collection_type_id'] = $collectionType->id;

        $collection = Collection::create($validated);

        return to_route('admin.collection.show', [
            'collectionType' => $collectionType->type,
            'collection' => $collection->slug,
        ])->with('success', 'Collection created successfully');
    }

    public function show(CollectionType $collectionType, Collection $collection)
    {
        return Inertia::render('Admin/Collection/Show', compact('collectionType', 'collection'));
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
    public function update(UpdateCollectionRequest $request, CollectionType $collectionType, Collection $collection)
    {
        $validated = $request->validated();

        $this->validateBlock($validated, $collectionType);

        $collection->update($validated);

        return to_route('admin.collection.show', [
            'collectionType' => $collectionType->type,
            'collection' => $collection->slug,
        ])->with('success', 'Collection created successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        $collection->load([
            'collectionType',
        ]);

        $collectionType = $collection->collectionType;

        $collection->delete();

        return to_route('admin.collection-type.show', $collectionType)->with('success', 'Collection deleted successfully');
    }
}
