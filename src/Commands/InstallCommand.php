<?php

namespace BalajiDharma\LaravelAdmin\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class InstallCommand extends Command
{
    protected $signature = 'laravel-admin:install
                            {--force : Force the installation}';

    protected $description = 'Install the Laravel Admin package';

    public function handle()
    {
        $force = $this->option('force') ?? false;
        if ($force) {
            $this->info('Forcing installation...');
        }
        $this->info('Installing Laravel Admin...');

        $this->info('Publishing assets...');
        $this->call('vendor:publish', [
            '--tag' => 'admin-core',
            '--force' => $force
        ]);
        $this->call('vendor:publish', [
            '--tag' => 'laravel-admin-resources',
            '--force' => $force
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Permission\PermissionServiceProvider',
            '--force' => $force
        ]);
        $this->call('vendor:publish', [
            '--provider' => 'Spatie\Activitylog\ActivitylogServiceProvider',
            '--tag' => 'activitylog-migrations',
            '--force' => $force
        ]);

        if ($this->confirm('Do you want to run migrations and seed the database?')) {
            $this->info('Running migrations and seeding...');
            $this->call('migrate', [
                '--seed' => true,
                '--seeder' => 'AdminCoreSeeder',
                '--force' => $force,
            ]);
        }

        $this->info('Linking storage...');
        $this->call('storage:link');

        $this->checkViteConfig();

        $this->info('Laravel Admin installed successfully.');
    }

    protected function checkViteConfig()
    {
        $viteConfigPath = base_path('vite.config.js');
        $viteConfigTsPath = base_path('vite.config.ts');

        if (File::exists($viteConfigPath) || File::exists($viteConfigTsPath)) {
            $this->info('Please make sure your Vite config includes:');
            $this->info('input: [');
            $this->info('    "resources/css/app.css",');
            $this->info('    "resources/js/app.js",');
            $this->info('    "resources/js/vendor/laravel-admin/app.js",');
            $this->info('    "resources/js/vendor/laravel-admin/form-builder/field.js",');
            $this->info('    "resources/css/vendor/laravel-admin/app.scss",');
            $this->info('],');
        }
    }
}
