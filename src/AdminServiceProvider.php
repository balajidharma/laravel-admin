<?php

namespace BalajiDharma\LaravelAdmin;

use BalajiDharma\LaravelAdmin\Composers\MenuComposer;
use Symfony\Component\Console\Output\ConsoleOutput;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AdminServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('laravel-admin::layouts.navigation', MenuComposer::class);
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-admin');
        $this->registerRoutes();

        if (app()->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/js' => resource_path('js/vendor/laravel-admin'),
                __DIR__ . '/../resources/css' => resource_path('css/vendor/laravel-admin'),
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laravel-admin'),
                __DIR__ . '/../tailwind.admin.config.js' => base_path('tailwind.admin.config.js'),
            ], ['laravel-admin-resources']);

            // Check if the main app's vite config includes our paths
            $this->checkViteConfig();
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

    protected function checkViteConfig()
    {
        $viteConfigPath = base_path('vite.config.js');
        $viteConfigTsPath = base_path('vite.config.ts');

        if (File::exists($viteConfigPath) || File::exists($viteConfigTsPath)) {
            $output = new ConsoleOutput();
            $output->writeln('Please make sure your Vite config includes:');
            $output->writeln('input: [');
            $output->writeln('    "resources/css/app.css",');
            $output->writeln('    "resources/js/app.js",');
            $output->writeln('    "resources/js/vendor/venor/laravel-admin/admin/app.js",');
            $output->writeln('    "resources/js/vendor/venor/laravel-admin/form-builder/field.js",');
            $output->writeln('    "resources/css/vendor/laravel-admin/admin/app.scss"');
            $output->writeln('],');
        }
    }
}
