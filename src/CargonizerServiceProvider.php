<?php

namespace Quicktech\Cargonizer;

use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

/**
 * This file is part of Quicktech\Cargonizer package,
 * a wrapper solution for Laravel to consume Cargonizer API.
 *
 * @license MIT
 * @package Quicktech\Cargonizer
 */
class CargonizerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        // Publish config files
        $this->publishes([
            __DIR__.'/../config/config.php' => app()->basePath() . '/config/cargonizer.php',
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCargonizer();
        $this->mergeConfig();
    }

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerCargonizer()
    {
        $this->app->bind('cargonizer', function ($app) {
            $httpClient = new Client([
                'base_uri' => $app['config']->get('cargonizer.endpoint'),
                'headers' => [
                    'X-Cargonizer-Key'    => $app['config']->get('cargonizer.credentials.key'),
                    'X-Cargonizer-Sender' => $app['config']->get('cargonizer.sender'),
                    'Content-type'        => 'application/xml'
                ]
            ]);

            return new CargonizerManager($httpClient);
        });

        // $this->app->alias('cargonizer', 'Quicktech\Cargonizer\Cargonizer');
    }

    /**
     * Merges user's and cargonizer's configs.
     *
     * @return void
     */
    private function mergeConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'cargonizer'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cargonizer'];
    }
    
}