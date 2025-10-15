<?php

namespace BalajiDharma\LaravelAdmin;

use BalajiDharma\LaravelAdmin\Composers\MenuComposer;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('admin::layouts.navigation', MenuComposer::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'admin');
        $this->registerRoutes();
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
