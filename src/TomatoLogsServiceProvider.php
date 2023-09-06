<?php

namespace TomatoPHP\TomatoLogs;

use Illuminate\Support\ServiceProvider;
use TomatoPHP\TomatoAdmin\Facade\TomatoMenu;
use TomatoPHP\TomatoLogs\Menus\LogsMenu;
use TomatoPHP\TomatoAdmin\Services\Contracts\Menu;
use TomatoPHP\TomatoRoles\Services\Permission;
use TomatoPHP\TomatoRoles\Services\TomatoRoles;


class TomatoLogsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //Register generate command
        $this->commands([
           \TomatoPHP\TomatoLogs\Console\TomatoLogsInstall::class,
        ]);

        //Register Config file
        $this->mergeConfigFrom(__DIR__.'/../config/tomato-logs.php', 'tomato-logs');

        //Publish Config
        $this->publishes([
           __DIR__.'/../config/tomato-logs.php' => config_path('tomato-logs.php'),
        ], 'tomato-logs-config');

        //Register Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        //Publish Migrations
        $this->publishes([
           __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'tomato-logs-migrations');

        //Register views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'tomato-logs');

        //Publish Views
        $this->publishes([
           __DIR__.'/../resources/views' => resource_path('views/vendor/tomato-logs'),
        ], 'tomato-logs-views');

        //Register Langs
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'tomato-logs');

        //Publish Lang
        $this->publishes([
           __DIR__.'/../resources/lang' => app_path('lang/vendor/tomato-logs'),
        ], 'tomato-logs-lang');

        //Register Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        $this->registerPermissions();
    }

    public function boot(): void
    {
        TomatoMenu::register([
            Menu::make()
                ->group(trans('tomato-logs::global.group'))
                ->label(trans('tomato-logs::global.title'))
                ->icon("bx bx-history")
                ->route("admin.logs.index"),
        ]);
    }

    /**
     * @return void
     */
    private function registerPermissions(): void
    {
        if(class_exists(TomatoRoles::class)){
            TomatoRoles::register(Permission::make()
                ->name('admin.logs.index')
                ->guard('web')
                ->group('logs')
            );

            TomatoRoles::register(Permission::make()
                ->name('admin.logs.file')
                ->guard('web')
                ->group('logs')
            );

            TomatoRoles::register(Permission::make()
                ->name('admin.logs.show')
                ->guard('web')
                ->group('logs')
            );

            TomatoRoles::register(Permission::make()
                ->name('admin.logs.destroy')
                ->guard('web')
                ->group('logs')
            );
        }
    }
}
