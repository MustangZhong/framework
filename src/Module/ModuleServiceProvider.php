<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2016, iBenchu.org
 * @datetime 2016-12-02 20:29
 */
namespace Notadd\Foundation\Module;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\ServiceProvider;
use Notadd\Foundation\Module\Commands\GenerateCommand;
use Notadd\Foundation\Module\Commands\ListCommand;
use Notadd\Foundation\Module\Listeners\CsrfTokenRegister;
use Notadd\Foundation\Module\Listeners\RouteRegister;

/**
 * Class ModuleServiceProvider.
 */
class ModuleServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * ModuleServiceProvider constructor.
     *
     * @param \Illuminate\Contracts\Foundation\Application $app
     * @param \Illuminate\Filesystem\Filesystem            $files
     */
    public function __construct(Application $app, Filesystem $files)
    {
        parent::__construct($app);
        $this->files = $files;
    }

    /**
     * Boot service provider.
     */
    public function boot()
    {
        $this->app->make(Dispatcher::class)->subscribe(CsrfTokenRegister::class);
        $this->app->make(Dispatcher::class)->subscribe(RouteRegister::class);
        $this->app->make(ModuleManager::class)->getModules()->each(function (Module $module, $path) {
            if ($this->files->isDirectory($path)) {
                if(is_string($module->getEntry()) && class_exists($module->getEntry())) {
                    $this->app->register($module->getEntry());
                } else {
                }
            }
            if ($this->files->isDirectory($path) && is_string($module->getEntry())) {
                if (!class_exists($module->getEntry())) {
                    if ($this->files->exists($autoload = $path . DIRECTORY_SEPARATOR . 'vendor' .DIRECTORY_SEPARATOR . 'autoload.php')) {
                        $this->files->requireOnce($autoload);
                        if (!class_exists($module->getEntry())) {
                            throw new \Exception('Module load fail!');
                        }
                    } else {
                        throw new \Exception('Module load fail!');
                    }
                }
                $this->app->register($module->getEntry());
            }
        });
        $this->commands([
            GenerateCommand::class,
            ListCommand::class
        ]);
    }

    /**
     * Register for service provider.
     */
    public function register()
    {
        $this->app->singleton('module', function ($app) {
            return new ModuleManager($app, $app['events'], $app['files']);
        });
    }
}
