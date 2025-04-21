<?php

namespace Tuna976\NEWS\Http\Controllers\News;

use Illuminate\Http\Request;
use Tuna976\NEWS\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserSubmissionController extends Controller
{
    /**
     * Show the form for submitting a new post
     */
    public function create()
    {
        return view('news::submissions.create');
    }
    
    /**
     * Store a newly submitted post
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|min:5|max:255',
            'content' => 'required|min:20',
            'source_url' => 'nullable|url',
        ]);
        
        $post = new Post();
        $post->category_id = 1;
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->slug = Str::slug($validated['title']) . '-' . time();
        $post->user_id = Auth::id() ?? 1; // Default to system if not logged in
        $post->is_published = 0; // All user submissions start as pending
        $post->published_at = now();
        $post->source_url = $validated['source_url'] ?? null;
        $post->save();
        
        return redirect()->route('news::submissions.thank-you')
            ->with('success', 'Your post has been submitted for review!');
    }
    
    /**
     * Show thank you page after submission
     */
    public function thankYou()
    {
        return view('news::submissions.thank-you');
    }
    
    /**
     * Display a user's posts by user ID - Only accessible by the owner
     * 
     * @param int $userId
     * @return \Illuminate\View\View
     */
    public function userPosts($userId)
    {
        // Check if user is logged in and matches the requested profile
        if (!Auth::check() || Auth::id() != $userId) {
            // Redirect to public profile instead
            return redirect()->route('news::submissions.my-posts')
                ->with('error', 'You can only view your own private posts page.');
        }
        
        $user = \App\Models\User::findOrFail($userId);
        
        $posts = Post::where('user_id', $userId)
                    ->orderBy('created_at', 'desc')  // Show all posts, including unpublished
                    ->paginate(10);
                    
        return view('news::submissions.user-posts', compact('posts', 'user'));
    }
    
    /**
     * Display public user profile with published posts
     */
    public function myPosts()
    {
        // This is a public profile, no authentication needed
        $user = Auth::user();
        $posts = Post::where('user_id', Auth::id())
                    ->where('is_published', 1) // Only published posts for public view
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
        return view('news::submissions.my-posts', compact('posts', 'user'));
    }
}
