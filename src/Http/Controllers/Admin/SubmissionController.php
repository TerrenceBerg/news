<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use Tuna976\NEWS\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubmissionController extends Controller
{
    /**
     * Display a listing of user submissions.
     */
    public function index()
    {
        $submissions = Post::where('user_id', '!=', null)
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);
                        
        return view('news::admin.submissions.index', compact('submissions'));
    }

    /**
     * Show the form for editing the submission.
     */
    public function edit($id)
    {
        $submission = Post::findOrFail($id);
        $categories = Category::all();
        
        return view('news::admin.submissions.edit', compact('submission', 'categories'));
    }

    /**
     * Update the submission in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:categories,id',
            'source_url' => 'nullable|url',
            'is_published' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $submission = Post::findOrFail($id);
        
        // Handle image upload if provided
        if ($request->hasFile('featured_image')) {
            // Store the image and get the path
            $imagePath = $request->file('featured_image')->store('posts', 'public');
            $submission->featured_image = $imagePath;
        }
        
        $submission->title = $validated['title'];
        $submission->content = $validated['content'];
        $submission->category_id = $validated['category_id'];
        $submission->source_url = $validated['source_url'] ?? null;
        $submission->is_published = $request->has('is_published') ? 1 : 0;
        
        // Only update slug if title changed
        if ($submission->isDirty('title')) {
            $submission->slug = Str::slug($validated['title']) . '-' . time();
        }
        
        $submission->save();
        
        return redirect()->route('news::admin.submissions.index')
                         ->with('success', 'Submission updated successfully!');
    }

    /**
     * Remove the submission from storage.
     */
    public function destroy($id)
    {
        $submission = Post::findOrFail($id);
        $submission->delete();
        
        return redirect()->route('news::admin.submissions.index')
                         ->with('success', 'Submission deleted successfully!');
    }

    /**
     * Quickly publish a submission.
     */
    public function publish($id)
    {
        $submission = Post::findOrFail($id);
        $submission->is_published = 1;
        $submission->save();
        
        return redirect()->route('news::admin.submissions.index')
                         ->with('success', 'Submission published successfully!');
    }
}
