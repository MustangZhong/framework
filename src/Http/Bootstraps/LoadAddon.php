<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-30 20:24
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Notadd\Foundation\Application;
use Notadd\Foundation\Addon\Events\AddonLoaded;
use Notadd\Foundation\Addon\Addon;
use Notadd\Foundation\Addon\AddonManager;
use Notadd\Foundation\Http\Contracts\Bootstrap;

/**
 * Class LoadExtension.
 */
class LoadAddon implements Bootstrap
{
    /**
     * @var \Illuminate\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Notadd\Foundation\Addon\AddonManager
     */
    protected $manager;

    /**
     * LoadExtension constructor.
     *
     * @param \Illuminate\Events\Dispatcher         $events
     * @param \Illuminate\Filesystem\Filesystem     $files
     * @param \Notadd\Foundation\Addon\AddonManager $manager
     */
    public function __construct(Dispatcher $events, Filesystem $files, AddonManager $manager)
    {
        $this->events = $events;
        $this->files = $files;
        $this->manager = $manager;
    }

    /**
     * Bootstrap the given application.
     *
     * @param \Notadd\Foundation\Application $application
     */
    public function bootstrap(Application $application)
    {
        $this->manager->repository()->enabled()->each(function (Addon $extension) use ($application) {
            $this->manager->registerExcept($extension->get('csrf', []));
            collect($extension->get('events', []))->each(function ($data, $key) {
                switch ($key) {
                    case 'subscribes':
                        collect($data)->each(function ($subscriber) {
                            if (class_exists($subscriber)) {
                                $this->events->subscribe($subscriber);
                            }
                        });
                        break;
                }
            });
            $application->register($extension->provider());
        });
        $this->events->dispatch(new AddonLoaded());
    }
}
