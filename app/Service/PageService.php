<?php

namespace App\Service;

use App\Concerns\hasDynamicValidation;
use App\Models\Block;
use App\Models\Page;

class PageService
{
    use hasDynamicValidation;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function updatePage($validated, Page $page)
    {
        return $this->savePage($validated, $page);
    }

    public function storePage($validated)
    {
        return $this->savePage($validated);
    }

    public function savePage($validated, ?Page $page = null)
    {
        // Get the page data excluding blocks
        $pageData = $this->extractPageData($validated);
        $blockTypes = $this->getBlockTypes($validated);

        // Validate all blocks based on their types
        $this->validateAllBlocks($validated, $blockTypes);

        if ($page) {
            $page->update($pageData);
        } else {
            $page = Page::create($pageData);
        }

        // Attach and update blocks to the page
        $this->syncBlocks($page, $validated);
        $this->syncForms($page, $validated);

        return $page;
    }

    private function syncBlocks(Page $page, $validated)
    {

        // Update blocks
        foreach ($validated['blocks'] as $validatedBlock) {
            $block = Block::findOrFail($validatedBlock['id']);

            $block->update([
                'content' => $validatedBlock['content'],
            ]);
        }

        $blocks = collect($validated['blocks'])->mapWithKeys(function (array $block, int $key) {
            return [$block['id'] => ['order' => $key]];
        });

        $page->blocks()->sync($blocks);
    }

    private function syncForms(Page $page, $validated)
    {
        // Update forms
        $forms = collect($validated['forms'])->mapWithKeys(function ($form) {
            return [$form['id'] => []];
        });

        $page->forms()->sync($forms);
    }

    private function extractPageData($validated)
    {
        return collect($validated)->except('blocks')->toArray();
    }

    private function getBlockTypes($validated)
    {
        // Get block types
        return collect($validated['blocks'])->map(function ($block) {
            return Block::find($block['id'])->blockType;
        })->all();
    }
}
