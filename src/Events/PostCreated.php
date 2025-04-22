<?php

namespace App\Events;

use App\Models\News\Post;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $post;

    public function __construct(Post $post)
    {
        $this->post = $post;
        Log::info('PostCreated event constructed', [
            'post_id' => $post->id,
            'post_title' => $post->title
        ]);
    }
}
