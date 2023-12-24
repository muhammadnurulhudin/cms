<?php
namespace Udiko\Cms;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Cache\RateLimiter;
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



    }
    /**
     * Summary of register
     * @return void
     */
    public function register()
    {
        Config::set('auth.providers.users.model', 'Udiko\Cms\Models\User');
        if (env('PUBLIC_PATH')) {
            $this->app->usePublicPath(base_path() . '/' . env('PUBLIC_PATH'));
        }
        if (\DB::connection()->getPDO()) {

            $this->app->bind('customRateLimiter', function ($app) {
                return new RateLimiter($app['cache']->driver('file'), $app['request'], 'loginpage', get_option('time_limit_login') ?? 3, get_option('limit_duration') ?? 60);
            });
            $this->app->bind('customRateLimiter', function ($app) {
                return new RateLimiter($app['cache']->driver('file'), $app['request'], md5(url()->full()), get_option('time_limit_reload') ?? 3, get_option('limit_duration') ?? 60);
            });

        }
    }

}
