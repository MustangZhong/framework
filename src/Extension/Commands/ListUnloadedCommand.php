<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-05-16 15:03
 */
namespace Notadd\Foundation\Extension\Commands;

use Illuminate\Support\Collection;
use Notadd\Foundation\Console\Abstracts\Command;
use Notadd\Foundation\Extension\ExtensionManager;

/**
 * Class ListUnloadedCommand.
 */
class ListUnloadedCommand extends Command
{
    /**
     * @var array
     */
    protected $headers = [
        'Extension Name',
        'Author',
        'Description',
        'Extension Path',
        'Entry',
        'Status',
    ];

    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->setDescription('Show unloaded extension list.');
        $this->setName('extension:unloaded');
    }

    /**
     * @param \Notadd\Foundation\Extension\ExtensionManager $manager
     *
     * @return bool
     */
    public function fire(ExtensionManager $manager)
    {
        $extensions = $manager->getUnloadedExtensions();
        $list = new Collection();
        $this->info('Unloaded extensions list:');
        $extensions->each(function (array $extension) use ($list) {
            $data = collect($extension['authors']);
            $author = $data->get('name');
            $data->has('email') ? $author .= ' <' . $data->get('email') . '>' : null;
            $list->push([
                $extension['identification'],
                $author,
                $extension['description'],
                $extension['directory'],
                $extension['provider'],
                'Normal',
            ]);
        });
        $this->table($this->headers, $list->toArray());

        return true;
    }
}