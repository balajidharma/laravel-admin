<?php

namespace BalajiDharma\LaravelAdmin;

use BalajiDharma\LaravelAdmin\AdminAuthServiceProvider;
use BalajiDharma\LaravelAdmin\Composers\MenuComposer;
use BalajiDharma\LaravelAdmin\Commands\InstallCommand;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(AdminAuthServiceProvider::class);
    }

    public function boot()
    {
        View::composer('laravel-admin::layouts.navigation', MenuComposer::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-admin');

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-admin'),
                __DIR__ . '/../stubs/package.json' => base_path('package.json'),
                __DIR__ . '/../stubs/postcss.config.js' => base_path('postcss.config.js'),
                __DIR__ . '/../stubs/app/Models' => base_path('app/Models'),
                __DIR__ . '/../stubs/app/Http/Controllers' => base_path('app/Http/Controllers'),
                __DIR__ . '/../stubs/app/Http/Middleware' => base_path('app/Http/Middleware'),
                __DIR__ . '/../stubs/app/Http/Requests' => base_path('app/Http/Requests'),
                __DIR__ . '/../stubs/app/View' => base_path('app/View'),
                __DIR__ . '/../stubs/config' => base_path('config'),
                __DIR__ . '/../stubs/migrations' => base_path('database/migrations'),
                __DIR__ . '/../stubs/routes' => base_path('routes'),
                __DIR__ . '/../stubs/bootstrap' => base_path('bootstrap'),
                __DIR__ . '/../stubs/resources/views' => base_path('resources/views'),
                __DIR__ . '/../stubs/resources/css' => base_path('resources/css'),
                __DIR__ . '/../stubs/resources/js' => base_path('resources/js'),
            ], ['laravel-admin-resources']);
        }
    }
}
