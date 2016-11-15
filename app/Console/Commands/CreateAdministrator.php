<?php

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use ProVision\Administration\AdminUser;
use ProVision\Administration\Http\Controllers\Systems\RolesRepairController;
use ProVision\Administration\Role;

class CreateAdministrator extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Managmend of admin users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct() {

        /*
         * command fix
         */
        $this->signature = config('provision_administration.command_prefix') . ':admin {email} {password}';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {

        /*
        * check is created user
        */
        $adminUser = AdminUser::where('email', $this->argument('email'))->first();

        if (empty($adminUser)) {
            /*
             * Create admin user
             */
            $adminUser = AdminUser::create([
                'name' => 'ProVision Administrator',
                'email' => $this->argument('email'),
                'password' => Hash::make($this->argument('password'))
            ]);

            $this->info('Creating admin user...');
            $this->info('email: ' . $adminUser->email);
            $this->info('password: ' . $this->argument('password'));
        } else {
            /*
             * Reset admin password
             */
            $adminUser->password = Hash::make($this->argument('password'));
            $adminUser->save();

            $this->info('Reset admin user...');
            $this->info('email: ' . $adminUser->email);
            $this->info('password: ' . $this->argument('password'));
        }

        /*
         * create default role and assing to user
         */
        $adminRole = Role::where('name', 'admin')->first();
        if (empty($adminRole)) {
            $adminRole = new Role();
            $adminRole->name = 'admin';
            $adminRole->display_name = 'Administrator';
            $adminRole->description = 'The BIG BOSS';
            $adminRole->save();
            $this->info('Create admin role...');
        }
        if (!$adminUser->hasRole('admin')) {
            $this->info('Assign admin role...');
            $adminUser->attachRole($adminRole);
        }

        /*
         * repair permissions
         */
        $permissionRepair = new RolesRepairController();
        $permissionRepair->index();
    }
}
