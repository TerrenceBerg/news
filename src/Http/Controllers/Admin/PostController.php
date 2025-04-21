<?php

namespace Tuna976\NEWS\Http\Controllers\Admin;

use Tuna976\NEWS\Events\PostCreated;
use App\Http\Controllers\Controller;
use Tuna976\NEWS\Models\Category;
use Tuna976\NEWS\Models\Post;
use Tuna976\NEWS\Models\PostImage;
use Tuna976\NEWS\Models\Tag;
use Tuna976\NEWS\Services\ImageOptimizationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PostController extends Controller
{
    protected $imageService;
    
    public function __construct(ImageOptimizationService $imageService)
    {
        $this->imageService = $imageService;
    }
    
    public function index(): View
    {
        $posts = Post::with(['user', 'category'])->latest()->paginate(10);
        return view('news::admin.posts.index', compact('posts'));
    }

    public function create(): View
    {
        $categories = Category::all();
        $tags = Tag::all();
        return view('news::admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'source_url' => 'nullable|url|max:2048',
            'excerpt' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:2048',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Start database transaction
        \DB::beginTransaction();
        try {
            // Generate unique slug
            $slug = Str::slug($request->title);
            $count = Post::where('slug', 'like', $slug . '%')->count();
            if ($count > 0) {
                $slug = $slug . '-' . ($count + 1);
            }

            // Handle featured image
            $imagePath = null;
            if ($request->hasFile('featured_image')) {
                $file = $request->file('featured_image');
                $imagePath = $this->imageService->convertToWebP(
                    $file,
                    'posts/' . time() . '-' . $file->getClientOriginalName()
                );
            }

            // Create post
            $post = Post::create([
                'title' => $request->title,
                'slug' => $slug,
                'content' => $request->content,
                'source_url' => $request->source_url,
                'excerpt' => $request->excerpt,
                'is_published' => $request->has('is_published'),
                'user_id' => auth()->id(),
                'category_id' => $request->category_id,
                'featured_image' => $imagePath,
                'published_at' => $request->has('is_published') ? now() : null,
            ]);

            // Sync tags if any
            if ($request->has('tags')) {
                $post->tags()->sync($request->tags);
            }

            // Commit transaction
            \DB::commit();

            // Fire the PostCreated event after successful creation
            try {
                Log::info('Dispatching PostCreated event', [
                    'post_id' => $post->id,
                    'admin_email' => config('mail.from.address')
                ]);
                
                // Force eager loading of relationships needed by notification
                $post->load(['user', 'category']);
                
                // Dispatch event immediately
                event(new PostCreated($post));
                
                Log::info('PostCreated event dispatched successfully');
                
                return redirect()->route('news::admin.posts.index')
                    ->with('success', 'Post created successfully! Notification email sent.');
            } catch (\Exception $e) {
                Log::error('Failed to send post notification', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return redirect()->route('news::admin.posts.index')
                    ->with('success', 'Post created successfully!')
                    ->with('warning', 'Failed to send notification email: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Failed to create post', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()->withInput()->withErrors(['error' => 'Failed to create post: ' . $e->getMessage()]);
        }
    }

    public function edit(Post $post): View
    {
        $categories = Category::all();
        $tags = Tag::all();
        $images = $post->images;
        return view('news::admin.posts.edit', compact('post', 'categories', 'tags', 'images'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|min:3',
            'content' => 'required',
            'source_url' => 'nullable|url|max:2048',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|max:1024',
        ]);
        
        $post->title = $validated['title'];
        $post->content = $validated['content'];
        $post->source_url = $request->source_url;
        $post->excerpt = $request->excerpt;
        $post->category_id = $validated['category_id'];
        $post->is_published = $request->has('is_published');
        
        if ($post->is_published && !$post->published_at) {
            $post->published_at = now();
        }
        
        if ($request->hasFile('featured_image')) {
            // Convert featured image to WebP
            $basePath = 'featured-images/' . Str::random(10) . '-' . time();
            $webpPath = $this->imageService->convertToWebP(
                $request->file('featured_image'),
                $basePath
            );
            
            // Save the WebP path or fall back to original upload
            $post->featured_image = $webpPath ?? $request->file('featured_image')->store('featured-images', 'public');
        }
        
        $post->save();
        
        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        } else {
            $post->tags()->detach();
        }
        
        return redirect()->route('news::admin.posts.index')
            ->with('message', 'Post updated successfully!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();
        return redirect()->route('news::admin.posts.index')->with('message', 'Post deleted successfully');
    }

    /**
     * Upload an image to a post.
     */
    public function uploadImage(Request $request, Post $post): JsonResponse
    {
        try {
            $request->validate([
                'image' => 'required|image|max:2048',
            ]);
            
            if (!$request->hasFile('image')) {
                return response()->json([
                    'success' => false,
                    'message' => 'No image file found in request',
                ], 400);
            }
            
            // Convert to WebP
            $basePath = 'post-images/' . $post->id . '/' . Str::random(10) . '-' . time();
            $webpPath = $this->imageService->convertToWebP(
                $request->file('image'),
                $basePath
            );
            
            // Use WebP path or fall back to original
            $path = $webpPath ?? $request->file('image')->store('post-images/' . $post->id, 'public');
            
            $maxOrder = $post->images()->max('sort_order') ?? 0;
            
            $image = $post->images()->create([
                'path' => $path,
                'alt_text' => $post->title,
                'sort_order' => $maxOrder + 1,
            ]);
            
            return response()->json([
                'success' => true,
                'image' => $image,
                'url' => Storage::url($path),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error uploading image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Update image alt text.
     */
    public function updateImageAlt(Request $request, PostImage $postImage): JsonResponse
    {
        try {
            $validated = $request->validate([
                'alt_text' => 'nullable|string|max:255',
            ]);
            
            $postImage->update([
                'alt_text' => $validated['alt_text'],
            ]);
            
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    /**
     * Delete an image from a post.
     */
    public function deleteImage(PostImage $postImage): JsonResponse
    {
        // Check authorization
        if ($postImage->post->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        // Delete the file
        if (Storage::disk('public')->exists($postImage->path)) {
            Storage::disk('public')->delete($postImage->path);
        }
        
        // Delete the record
        $postImage->delete();
        
        return response()->json(['success' => true]);
    }
    
    /**
     * Reorder post images.
     */
    public function reorderImages(Request $request): JsonResponse
    {
        $request->validate([
            'images' => 'required|array',
            'images.*.id' => 'required|exists:post_images,id',
            'images.*.order' => 'required|integer|min:0',
        ]);
        
        foreach ($request->images as $image) {
            PostImage::where('id', $image['id'])->update(['sort_order' => $image['order']]);
        }
        
        return response()->json(['success' => true]);
    }
}
