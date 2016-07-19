<?php
namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;


class MigrateRollback extends Command {
    /**
     * Name of the command.
     *
     * @param string
     */
    protected $name = 'admin:migrate-rollback';

    /**
     * Necessary to let people know, in case the name wasn't clear enough.
     *
     * @param string
     */
    protected $description = 'Migrate:rollback Administration database';

    /**
     * Setup the application container as we'll need this for running migrations.
     */
    public function __construct(Container $app) {
        $this->app = $app;
        /*
        * command fix
        */
        $this->signature = config('provision_administration.command_prefix') . ':migrate-rollback';

        parent::__construct();
    }

    /**
     * Run the package migrations.
     */
    public function handle() {
        $path = str_ireplace(base_path(), '', realpath(__DIR__ . '\\..\\..\\..\\database\\migrations'));
        \Artisan::call('migrate:rollback', ['--path' => $path]);
        $this->info(\Artisan::output());
    }
}