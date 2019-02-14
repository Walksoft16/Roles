<?php

namespace Walksoft\Roles;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class RolesServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var  bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return  void
     */
    public function boot()
    {
        // Get namespace
        $nameSpace = $this->app->getNamespace();

        AliasLoader::getInstance()->alias('RolesAppController', $nameSpace . 'Http\Controllers\Controller');

        // Routes
        $this->app->router->group(['namespace' => $nameSpace . 'Http\Controllers'], function () {
            require __DIR__ . '/../routes/web.php';
        });

        // Load Views
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'Roles');

        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'Roles');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
       //$this->loadRoutesFrom(__DIR__.'/../routes/web.php');
    }

    public function register()
    {
    }

}
