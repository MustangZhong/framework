<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-30 20:19
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Notadd\Foundation\Application;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Module\Module;
use Notadd\Foundation\Module\ModuleLoaded;
use Notadd\Foundation\Module\ModuleManager;

/**
 * Class LoadModule.
 */
class LoadModule implements Bootstrap
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
     * @var \Notadd\Foundation\Module\ModuleManager
     */
    protected $manager;

    /**
     * LoadModule constructor.
     *
     * @param \Illuminate\Events\Dispatcher                       $events
     * @param \Illuminate\Filesystem\Filesystem                   $files
     * @param \Notadd\Foundation\Module\ModuleManager             $manager
     */
    public function __construct(Dispatcher $events, Filesystem $files, ModuleManager $manager)
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
        if ($application->isInstalled()) {
            $this->manager->repository()->enabled()->each(function (Module $module) use ($application) {
                $this->manager->registerExcept($module->get('csrf', []));
                collect($module->get('events', []))->each(function ($data, $key) {
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
                $providers = collect($application->make('config')->get('app.providers'));
                $providers->push($module->service());
                collect($module->get('providers', []))->each(function ($provider) use ($providers) {
                    $providers->push($provider);
                });
                $application->make('config')->set('app.providers', $providers->toArray());
            });
        }
        $this->events->dispatch(new ModuleLoaded());
    }
}
