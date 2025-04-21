<?php

namespace Tuna976\NEWS;

use Illuminate\Support\ServiceProvider;
use Tuna976\NEWS\Http\Middleware\AdminMiddleware;
use Tuna976\NEWS\Http\Middleware\EnsureUserHasRole;
 
class NEWSServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // ✅ Load Views (Ensure directory path is correct)
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'news');

        // ✅ Load Routes (Remove runningInConsole check)
        if (! $this->app->routesAreCached()) {
            $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        }
        // ✅ Load Middleware 
        // $kernel->pushMiddleware(AdminMiddleware::class);
        // $kernel->pushMiddleware(EnsureUserHasRole::class);
        // $router->middlewareGroup('admin', [ 
        //     Tuna976\NEWS\Http\Middleware\AdminMiddleware::class, 
        // ]);

        // ✅ Publish resources 
        $this->publishes([
            __DIR__.'/Http/Middleware' => resource_path('../app/Http/Middleware'),
        ], 'news-middleware');

        // $this->publishes([
        //     __DIR__.'/resources/views/js/serviceworker.js' => resource_path('../public/js/serviceworker.js'),
        // ], 'news-serviceworker');

        // ✅ Publish Config (Ensure correct path)
        $this->publishes([
            __DIR__.'/Config/news-config.php' => config_path('news-config.php'),
        ], 'news-config');

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