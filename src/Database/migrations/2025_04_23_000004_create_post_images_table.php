<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('news_post_images')) {
            Schema::create('news_post_images', function (Blueprint $table) {
                $table->id();
                $table->foreignId('news_post_id')->constrained()->onDelete('cascade');
                $table->string('path');
                $table->string('alt_text')->nullable();
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('news_post_images');
    }
};
