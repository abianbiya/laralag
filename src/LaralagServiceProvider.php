<?php

namespace Abianbiya\Laralag;

use App\Models\User;
use Livewire\Livewire;
use Abianbiya\Laralag\Laralag;
use Illuminate\Routing\Router;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Abianbiya\Laralag\Commands\GenerateModule;
use Abianbiya\Laralag\Commands\LaralagInstall;
use Abianbiya\Laralag\Commands\SyncPermission;
use Abianbiya\Laralag\View\Components\Sidebar;
use Abianbiya\Laralag\Commands\RunLaralagSeeder;
use Abianbiya\Laralag\Middleware\CheckPermission;

class LaralagServiceProvider extends ServiceProvider
{
    public $baseDir = __DIR__ . '/../';

    public function boot()
    {
        // Publish assets
        $this->publishes([
            $this->baseDir . 'resources/assets' => public_path('build'),
        ], 'laralag_assets');

        // Publish config
        $this->publishes([
            $this->baseDir . 'config/laralag.php' => config_path('laralag.php'),
        ], 'laralag_config');

        // Publish Modules
        // $this->publishes([
        //     $this->baseDir . 'src/Modules' => base_path('app/Modules'),
        // ], 'laralag_modules');

        // Publish Livewire
        // $this->publishes([
        //     $this->baseDir . 'src/Livewire' => base_path('app/Livewire'),
        // ], 'laralag_modules');

        // Publishing the views.
        Blade::component('laralag-sidebar', Sidebar::class);
        // Blade::component('laralag-breadcrumb', Bread::class);
        $this->publishes([
			$this->baseDir.'resources/views' => resource_path('views'),
		], 'laralag_views');

        // Load migrations
        $this->loadMigrationsFrom($this->baseDir . 'database/migrations');
        $this->loadRoutesFrom($this->baseDir . 'routes/web.php');
        $this->loadViewsFrom($this->baseDir . 'resources/views', 'Laralag');
        $this->loadTranslationsFrom($this->baseDir . 'resources/lang', 'laralag');

        // Publishing the translation files.
        /*$this->publishes([
			__DIR__.'/../resources/lang' => resource_path('lang/vendor/laralag'),
		], 'lang');*/

        // Registering package commands.
        // $this->commands([]);

        Livewire::component('auth.login', \Abianbiya\Laralag\Livewire\Auth\Login::class);
        Livewire::component('auth.register', \Abianbiya\Laralag\Livewire\Auth\Register::class);
        Livewire::component('auth.forget-password', \Abianbiya\Laralag\Livewire\Auth\ForgetPassword::class);
        Livewire::component('auth.new-password', \Abianbiya\Laralag\Livewire\Auth\NewPassword::class);

        $this->moduleLoader();
        $this->appModuleLoader();

        $router = $this->app->make(Router::class);

        // Register route middleware
        $router->aliasMiddleware('permission', CheckPermission::class);

        Paginator::useBootstrap();
        Gate::before(function (User $user, $ability) {
            return $user->hasPermission($ability) ?: null;
        });
    }

    public function register()
    {
        // Register the command
        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateModule::class,
                LaralagInstall::class,
                RunLaralagSeeder::class,
                SyncPermission::class,
            ]);
        }

        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/laralag.php', 'laralag');

        // Register the main class to use with the facade
        $this->app->singleton('laralag', function () {
            return new Laralag;
        });
    }

    public function moduleLoader()
    {
        $modulepath = $this->baseDir.'src/Modules';
        $modules = File::directories($modulepath);

        foreach ($modules as $module) {
            $routefile = $module . '/routes.php';
            // load routes
            if (file_exists($routefile)) {
                // include $routefile;
                $this->loadRoutesFrom($routefile);
            }

            // load views
            $viewdir = $module . '/Views';
            if (is_dir($viewdir)) {
                $modulename = @end(explode("/", $module));
                $this->loadViewsFrom($viewdir, $modulename);
            }
        }
    }

    public function appModuleLoader()
    {
        $modulepath = app_path('Modules');
        if(!is_dir($modulepath)) {
            return;
        }
        $modules = File::directories($modulepath);

        foreach ($modules as $module) {
            $routefile = $module . '/routes.php';
            // load routes
            if (file_exists($routefile)) {
                // include $routefile;
                $this->loadRoutesFrom($routefile);
            }

            // load views
            $viewdir = $module . '/Views';
            if (is_dir($viewdir)) {
                $modulename = @end(explode("/", $module));
                $this->loadViewsFrom($viewdir, $modulename);
            }
        }
    }
    
}
