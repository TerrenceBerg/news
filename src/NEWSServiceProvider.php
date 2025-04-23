<?php

namespace Tuna976\NEWS;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Livewire\Admin\PostForm;
use App\Http\Livewire\News\CommentSection;

class NEWSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ Load Views (Ensure directory path is correct)
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'news');

        // Register Livewire Component - Fix namespace references
        Livewire::component('news-posts-form', PostForm::class);
        Livewire::component('news-comment-section', CommentSection::class);

        // ✅ Load Routes (Remove runningInConsole check)
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        }

        // ✅ Publish resources 
        $this->publishes([
            __DIR__.'/Config/news-config.php' => config_path('news-config.php'),
            __DIR__.'/Public' => public_path(''),
            __DIR__.'/Http/Middleware' => app_path('/Http/Middleware'),
            __DIR__.'/Http//Controllers/News' => app_path('/Http/Controllers/News'),
            __DIR__.'/Http/Livewire/News' => app_path('/Http/Livewire/News'),
            __DIR__.'/Models/News' => app_path('/Models/News'),
            __DIR__.'/Services/News' => app_path('/Services/News'),
            __DIR__.'/Events' => app_path('/Events'),
            __DIR__.'/Resources/views/news' => resource_path('/views/vendor/News'),
            __DIR__.'/Resources/views/vendor/pagination' => resource_path('/views/vendor/pagination'),
        ], 'news-files');

        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
    }

    public function register()
    {
        // ✅ Load Config (Ensure correct path)
        $this->mergeConfigFrom(__DIR__.'/Config/news-config.php', 'news-config');
        // ✅ Bind Calendar Service
        $this->app->singleton('NEWS', function ($app) {
            return new NEWS();
        });
    }

}