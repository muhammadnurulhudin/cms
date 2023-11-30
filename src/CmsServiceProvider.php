<?php
namespace Udiko\Cms;
use Illuminate\Support\ServiceProvider;
use Config;
class CmsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        require_once(__DIR__ . "/Inc/Helpers.php");
        $this->mergeConfigFrom(__DIR__ . "/config/modules.php", "modules");
        load_default_module();
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");
        $this->loadViewsFrom(__DIR__ . "/views", "views");
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
        $this->publishes([
            __DIR__ . '/public' => public_path('/'),
            __DIR__ . '/views/errors' => resource_path('views/errors'),
            __DIR__ . '/views/template' => resource_path('views/template'),
            __DIR__ . '/Models/User.php' => app_path('Models/User.php'),
        ], 'cms');
        cache_content_initial();
    }
    /**
     * Summary of register
     * @return void
     */
    public function register()
    {
        Config::set('auth.providers.users.model', 'Udiko\Cms\Models\User');
    }


}
