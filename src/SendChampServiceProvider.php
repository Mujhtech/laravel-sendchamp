<?php

/*
 *
 * (c) Muhideen Mujeeb Adeoye <mujeeb.muhideen@gmail.com>
 *
 */

namespace Mujhtech\SendChamp;

use Illuminate\Support\ServiceProvider;

class SendChampServiceProvider extends ServiceProvider
{
    /*
    * Indicates if loading of the provider is deferred.
    *
    * @var bool
    */

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind('laravel-sendchamp', function () {
            return new SendChamp;
        });
    }

    /**
     * Publishes all the config file this package needs to function
     */
    public function boot()
    {
        $config = realpath(__DIR__.'/../config/sendchamp.php');

        $this->publishes([
            $config => config_path('sendchamp.php'),
        ]);
    }

    /**
     * Get the services provided by the provider
     *
     * @return array
     */
    public function provides()
    {
        return ['laravel-sendchamp'];
    }
}
