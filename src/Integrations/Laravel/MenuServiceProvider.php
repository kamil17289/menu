<?php

namespace Nethead\Menu\Integrations\Laravel;

use Illuminate\Support\ServiceProvider;
use Nethead\Menu\Repository;

/**
 * Class MenuServiceProvider
 * @package Nethead\Integrations\Laravel
 */
class MenuServiceProvider extends ServiceProvider {
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('menu', function($app) {
            return new Repository();
        });
    }
}