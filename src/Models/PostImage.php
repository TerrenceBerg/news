<?php

namespace Tuna976\NEWS\Models;
use Tuna976\NEWS\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Tuna976\NEWS\Services\ImageOptimizationService;

class PostImage extends Model
{
    protected $fillable = [
        'post_id',
        'path',
        'alt_text',
        'sort_order',
    ];
    
    /**
     * Get optimized WebP image URL with fallback
     * 
     * @return string
     */
    public function getWebpUrlAttribute(): string
    {
        return ImageOptimizationService::getResponsiveImageUrl($this->path);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
