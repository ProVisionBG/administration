<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use ProVision\Administration\Media;

class MediaResize extends Command
{
    /**
     * Name of the command.
     *
     * @param string
     */
    protected $name = 'admin:media-resize';

    /**
     * Necessary to let people know, in case the name wasn't clear enough.
     *
     * @param string
     */
    protected $description = 'Resize all items in Media';

    public function __construct()
    {
        /*
        * command fix
        */
        $this->signature = config('provision_administration.command_prefix').':media-resize';

        parent::__construct();
    }

    /**
     * Run the package migrations.
     */
    public function handle()
    {
        Media::chunk(100, function ($media) {
            foreach ($media as $m) {
                /*
                 * remove old sizes
                 */
                $filesInDirectory = \File::files(realpath(public_path($m->path)));
                if (! empty($filesInDirectory)) {
                    //var_dump($filesInDirectory);
                    foreach ($filesInDirectory as $file) {
                        //дали е размер или оригинал? - запазваме оригинала
                        if (strstr(basename($file), '_')) {
                            \File::delete($file);
                        }
                    }
                }

                /*
                 * make new sizes
                 */
                $m->quickResize();

                $this->info('#'.$m->id.': resized');
            }
        });

        $this->info($this->signature.' END');
    }
}
