<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-30 16:35
 */
namespace Notadd\Foundation\Extension\Subscribers;

use Notadd\Foundation\Extension\Controllers\ExtensionsController;
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
        $this->router->group(['middleware' => ['auth:api', 'cross', 'web'], 'prefix' => 'api/administration'], function () {
            $this->router->resource('extensions', ExtensionsController::class)->methods([
                'destroy' => 'uninstall',
                'index' => 'list',
                'store' => 'install',
            ])->names([
                'destroy' => 'addons.uninstall',
                'index' => 'extensions.list',
                'store' => 'extensions.install',
            ])->only([
                'destroy',
                'index',
                'store',
            ]);
        });
    }
}
