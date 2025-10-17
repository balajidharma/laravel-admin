<?php

namespace BalajiDharma\LaravelAdmin;

use BalajiDharma\LaravelAdmin\AdminAuthServiceProvider;
use BalajiDharma\LaravelAdmin\Composers\MenuComposer;
use BalajiDharma\LaravelAdmin\Commands\InstallCommand;
use Illuminate\Support\Facades\Route;
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
        $this->registerRoutes();

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
            $this->publishes([
                __DIR__ . '/../resources/js' => resource_path('js/vendor/laravel-admin'),
                __DIR__ . '/../resources/css' => resource_path('css/vendor/laravel-admin'),
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-admin'),
                __DIR__ . '/../stubs/package.json' => base_path('package.json'),
                __DIR__ . '/../tailwind.admin.config.js' => base_path('tailwind.admin.config.js'),
                __DIR__ . '/../stubs/Models/Permission.php.stub' => base_path('app/Models/Permission.php'),
                __DIR__ . '/../stubs/Models/Role.php.stub' => base_path('app/Models/Role.php'),
                __DIR__ . '/../stubs/Models/User.php.stub' => base_path('app/Models/User.php'),
                __DIR__ . '/../stubs/migrations' => base_path('database/migrations'),
            ], ['laravel-admin-resources']);
        }
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::middleware('web')
             ->group(__DIR__.'/../routes/admin.php');
    }
}
