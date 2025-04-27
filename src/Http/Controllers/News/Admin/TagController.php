<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::latest()->paginate(10);
        return view('vendor.News.admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('vendor.News.admin.tags.create');
    }

    public function edit(Tag $tag): View
    {
        return view('vendor.News.admin.tags.edit', compact('tag'));
    }

    /**
     * Store a newly created tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:news_tags',
            'slug' => 'nullable|string|max:255|unique:news_tags',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        Tag::create($validated);
        
        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    /**
     * Update the specified tag in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:news_tags,name,' . $tag->id,
            'slug' => 'nullable|string|max:255|unique:news_tags,slug,' . $tag->id,
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        $tag->update($validated);
        
        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    /**
     * Remove the specified tag from storage.
     *
     * @param  \App\Models\News\Tag  $tag
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->delete();
        
        return redirect()->route('admin.tags.index')
            ->with('success', 'Tag deleted successfully.');
    }
}
