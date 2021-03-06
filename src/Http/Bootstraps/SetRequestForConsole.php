<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 11:07
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Illuminate\Http\Request;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Contracts\Bootstrap;

/**
 * Class SetRequestForConsole.
 */
class SetRequestForConsole implements Bootstrap
{
    /**
     * Bootstrap the given application.
     *
     * @param \Notadd\Foundation\Application $application
     */
    public function bootstrap(Application $application)
    {
        $url = $application->make('config')->get('app.url', 'http://localhost');
        $application->instance('request', Request::create($url, 'GET', [], [], [], $_SERVER));
    }
}
