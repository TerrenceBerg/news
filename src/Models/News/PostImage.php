<?php

namespace App\Models\News;
use App\Models\News\Post;
use Illuminate\Database\Eloquent\Model;
use App\Services\ImageOptimizationService;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
