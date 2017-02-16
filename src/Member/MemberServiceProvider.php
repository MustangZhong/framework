<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-09-24 17:27
 */
namespace Notadd\Foundation\Member;

use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Member\Commands\PermissionCommand;

/**
 * Class MemberServiceProvider.
 */
class MemberServiceProvider extends ServiceProvider
{
    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('member', function ($app) {
            return new MemberManagement($app);
        });
        $this->app->singleton('member.manager', function () {
            $manager = $this->app->make('member');

            return $manager->manager();
        });

        $this->app->bind('permission', function ($app) {
            return new PermissionManager;
        });

        $this->commands([
            PermissionCommand::class,
        ]);
    }
}
