<?php

namespace App\Models\News;
use App\Models\News\Tag;
use App\Models\News\Comment;
use App\Models\News\Category;
use App\Models\News\PostImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Tuna976\NEWS\Services\ImageOptimizationService;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'source_url', // Added source_url to fillable fields
        'featured_image',
        'is_published',
        'published_at',
        'user_id',
        'category_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PostImage::class)->orderBy('sort_order');
    }

    /**
     * Get the effective featured image path - uses first post image as fallback
     * 
     * @return string|null
     */
    public function getDisplayImageAttribute(): ?string
    {
        if ($this->featured_image) {
            return $this->featured_image;
        }
        
        // Use the first post image as fallback
        $firstImage = $this->images()->orderBy('sort_order')->first();
        
        return $firstImage ? $firstImage->path : null;
    }
    
    /**
     * Get optimized image URL with fallback
     * 
     * @return string
     */
    public function getDisplayImageUrlAttribute(): string
    {
        if (!$this->display_image) {
            return '';
        }
        
        return Storage::url($this->display_image);
    }
    
    /**
     * Get WebP image URL if available, or original as fallback
     * 
     * @return string
     */
    public function getDisplayImageUrlWebpAttribute(): string
    {
        return ImageOptimizationService::getResponsiveImageUrl($this->display_image);
    }
}
