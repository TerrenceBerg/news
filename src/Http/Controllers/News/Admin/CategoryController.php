<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('posts')->latest()->paginate(10);
        return view('vendor.News.admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('vendor.News.admin.categories.create');
    }
    
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'slug' => 'nullable|string|max:255|unique:categories',
            'description' => 'nullable|string',
        ]);
        
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }
        
        Category::create($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('message', 'Category created successfully!');
    }

    public function edit(Category $category): View
    {
        return view('vendor.News.admin.categories.edit', compact('category'));
    }
    
    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string',
        ]);
        
        $category->update($validated);
        
        return redirect()->route('admin.categories.index')
            ->with('message', 'Category updated successfully!');
    }
    
    public function destroy(Category $category): RedirectResponse
    {
        $category->delete();
        
        return redirect()->route('admin.categories.index')
            ->with('message', 'Category deleted successfully!');
    }
}
