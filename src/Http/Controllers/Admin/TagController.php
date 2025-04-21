<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $tags = Tag::withCount('posts')->latest()->paginate(10);
        return view('news::admin.tags.index', compact('tags'));
    }

    public function create(): View
    {
        return view('news::admin.tags.create');
    }

    public function edit(Tag $tag): View
    {
        return view('news::admin.tags.edit', compact('tag'));
    }
}
