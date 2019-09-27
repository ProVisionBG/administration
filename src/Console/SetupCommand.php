<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use ProVision\Administration\Models\Administrator;
use ProVision\Administration\Providers\AdministrationServiceProvider;
use Spatie\Permission\PermissionServiceProvider;

class SetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
         * command signature fix
         */
        $this->signature = config('administration.command_prefix') . ':setup';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {

        $bar = $this->output->createProgressBar(5);
        $bar->start();

        $bar->advance();
        $this->laravelPermissionSetup();

        $bar->advance();
        $this->publishAssets();

        $bar->advance();
        $this->migrate();

        $bar->advance();
        $this->permissions();

        $bar->advance();
        $this->checkForAdminAccount();

        $bar->finish();
    }

    /**
     * Setup https://github.com/spatie/laravel-permission
     *
     * @return void
     */
    private function laravelPermissionSetup(): void
    {
        $this->call('vendor:publish', [
            '--provider' => PermissionServiceProvider::class,
            '--tag' => 'migrations'
        ]);
    }

    /**
     * Publish assets
     *
     * @return void
     */
    private function publishAssets(): void
    {
        $this->call('vendor:publish', [
            '--provider' => AdministrationServiceProvider::class,
            '--tag' => 'assets',
            '--force' => true
        ]);
        $this->info('Assets published...');
    }

    /**
     * Run migrations
     *
     * @return void
     */
    private function migrate(): void
    {
        if ($this->confirm('Do you want run `migrate`?', true)) {
            $this->call('migrate');
        }
    }

    /**
     * Create default administrator account
     *
     * @return void
     */
    private function checkForAdminAccount(): void
    {
        $hasUsers = Administrator::count();

        if ($hasUsers > 0) {
            return;
        }

        if ($this->confirm('Do you want create administrator account?', true)) {
            $this->call(config('administration.command_prefix') . ':create');
        }
    }

    /**
     * Repair permissions
     *
     * @return void
     */
    private function permissions(): void
    {
        $this->call(config('administration.command_prefix') . ':permissions');
    }
}
