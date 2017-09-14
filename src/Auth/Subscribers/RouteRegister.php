<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-11-02 15:55
 */
namespace Notadd\Foundation\Auth\Subscribers;

use Notadd\Foundation\Auth\Controllers\AuthController;
use Notadd\Foundation\Routing\Abstracts\RouteRegister as AbstractRouteRegister;

/**
 * Class RouteRegister.
 */
class RouteRegister extends AbstractRouteRegister
{
    /**
     * Handle Route Register.
     */
    public function handle()
    {
        $this->router->group(['middleware' => 'web'], function () {
            $this->router->get($this->container->bound('auth.logout') ? $this->container->make('auth.logout') : 'logout',
                $this->container->bound('auth.logout.resolver') ? $this->container->make('auth.logout.resolver') : AuthController::class . '@logout');
            $this->router->resource($this->container->bound('auth.login') ? $this->container->make('auth.login') : 'login',
                $this->container->bound('auth.logout.resolver') ? $this->container->make('auth.logout.resolver') : AuthController::class);
        });
    }
}
