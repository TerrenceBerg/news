<?php

namespace App\Http\Controllers\News\Admin;

use App\Http\Controllers\Controller;
use App\Models\News\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount('posts')->latest()->paginate(10);
        return view('vendor.news.admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('vendor.news.admin.tags.create');
    }

    public function edit(Tag $tag): View
    {
        return view('vendor.news.admin.tags.edit', compact('tag'));
    }
}
