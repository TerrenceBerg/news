<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news_post_tag', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_post_id')->constrained('news_posts')->onDelete('cascade');
            $table->foreignId('news_tag_id')->constrained('news_tags')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news_post_tag');
    }
};
