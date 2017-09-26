<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-22 17:21
 */
namespace Notadd\Foundation\Passport;

use Carbon\Carbon;
use Laravel\Passport\Console\ClientCommand;
use Laravel\Passport\Console\InstallCommand;
use Laravel\Passport\Console\KeysCommand;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider as LaravelPassportServiceProvider;

/**
 * Class PassportServiceProvider.
 */
class PassportServiceProvider extends LaravelPassportServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->commands([
            ClientCommand::class,
            InstallCommand::class,
            KeysCommand::class,
        ]);
        Passport::tokensExpireIn(Carbon::now()->addHours(24));
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        Passport::cookie('notadd_token');
        $this->registerAuthorizationServer();
        $this->registerResourceServer();
        $this->registerGuard();
        $this->app->singleton('api', function ($app) {
            return new Passport($app, $app['events']);
        });
    }

    /**
     * Register the token guard.
     *
     * @return void
     */
    protected function registerGuard()
    {
        $this->app['auth']->extend('passport', function ($app, $name, array $config) {
            return tap($this->makeGuard($config), function ($guard) {
                $this->app->refresh('request', $guard, 'setRequest');
            });
        });
    }
}
