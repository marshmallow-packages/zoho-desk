<?php

namespace Marshmallow\ZohoDesk;

use Illuminate\Support\ServiceProvider;
use Marshmallow\ZohoDesk\Commands\ZohoDeskAuth;
use Marshmallow\ZohoDesk\Commands\ZohoDeskListDepartments;
use Marshmallow\ZohoDesk\Commands\ZohoDeskOrder;

class ZohoDeskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->mergeConfigFrom(
            __DIR__.'/../config/zohodesk.php',
            'zohodesk'
        );

        $this->commands([
            ZohoDeskAuth::class,
            ZohoDeskOrder::class,
            ZohoDeskListDepartments::class,
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        /*
         * Publish config
         */
        $this->publishes([
            __DIR__.'/../config/zohodesk.php' => config_path('zohodesk.php'),
        ]);
    }
}
