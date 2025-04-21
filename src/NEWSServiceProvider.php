<?php

namespace Tuna976\NEWS;

use Illuminate\Support\ServiceProvider;

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

        // ✅ Publish resources 
        // $this->publishes([
        //     __DIR__.'/Resources/views/images' => resource_path('../public/images'),
        // ], 'news-images');

        // $this->publishes([
        //     __DIR__.'/resources/views/js/serviceworker.js' => resource_path('../public/js/serviceworker.js'),
        // ], 'news-serviceworker');

        // ✅ Publish Config (Ensure correct path)
        $this->publishes([
            __DIR__.'/Config/news-config.php' => config_path('news-config.php'),
        ], 'config');

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