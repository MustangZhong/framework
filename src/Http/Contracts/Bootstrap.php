<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-22 18:18
 */
namespace Notadd\Foundation\Http\Contracts;

use Notadd\Foundation\Application;

/**
 * Interface Bootstrap.
 */
interface Bootstrap
{
    /**
     * Bootstrap the given application.
     *
     * @param \Notadd\Foundation\Application $application
     */
    public function bootstrap(Application $application);
}
