<?php
namespace Udiko\Cms;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Config;
class CmsServiceProvider extends ServiceProvider
{

    public function boot(Request $req)
    {
        require_once(__DIR__ . "/Inc/Helpers.php");
        $this->mergeConfigFrom(__DIR__ . "/config/modules.php", "modules");
        load_default_module();
        $this->loadViewsFrom(__DIR__ . "/views", "views");
        $this->loadMigrationsFrom(__DIR__ . "/database/migrations");
        $this->publishes([
            __DIR__ . '/public' => public_path('/'),
            __DIR__ . '/views/errors' => resource_path('views/errors'),
            __DIR__ . '/views/template' => resource_path('views/template')
        ], 'cms');
        if(file_exists(resource_path('views/template/'.template().'/modules.php'))){
            require_once(resource_path('views/template/' . template() . '/modules.php'));
            isset($config) ? config(['modules.config'=>$config]) : exit('No Config Found! Please define minimal  $config["web_type"] = "Your Web Type"; at path '.resource_path('views/template/' . template() . '/modules.php'));
        }
        $this->loadRoutesFrom(__DIR__ . "/routes/web.php");
        if(!db_connected()){
            exit('No Database!');
        }


    }
    /**
     * Summary of register
     * @return void
     */
    public function register()
    {
        Config::set('auth.providers.users.model', 'Udiko\Cms\Models\User');
        // $this->app->register(ImageServiceProvider::class);

    }


}
