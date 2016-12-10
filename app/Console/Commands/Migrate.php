<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class Migrate extends Command
{
    /**
     * Name of the command.
     *
     * @param string
     */
    protected $name = 'admin:migrate';

    /**
     * Necessary to let people know, in case the name wasn't clear enough.
     *
     * @param string
     */
    protected $description = 'Migrate Administration database';

    /**
     * Setup the application container as we'll need this for running migrations.
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        /*
        * command fix
        */
        $this->signature = config('provision_administration.command_prefix').':migrate';

        parent::__construct();
    }

    /**
     * Run the package migrations.
     */
    public function handle()
    {
        $path = str_ireplace(base_path(), '', realpath(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'database'.DIRECTORY_SEPARATOR.'migrations'));
        $this->info('Path to migrate (provision/administration): '.$path);
        \Artisan::call('migrate', ['--path' => $path]);
        $this->info(\Artisan::output());

        $this->info('Migrate: VentureCraft/revisionable');
        \Artisan::call('migrate', ['--path' => 'vendor/venturecraft/revisionable/src/migrations']);
        $this->info(\Artisan::output());
    }
}
