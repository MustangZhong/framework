<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 20:58
 */
namespace Notadd\Foundation\Database;

use Illuminate\Database\DatabaseServiceProvider as IlluminateDatabaseServiceProvider;
use Illuminate\Database\Eloquent\Model;

/**
 * Class DatabaseServiceProvider.
 */
class DatabaseServiceProvider extends IlluminateDatabaseServiceProvider
{
    /**
     * Boot service provider.
     */
    public function boot()
    {
        Model::setConnectionResolver($this->app['db']);
        Model::setEventDispatcher($this->app['events']);
    }
}
