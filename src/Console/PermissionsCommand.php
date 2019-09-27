<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use ProVision\Administration\Models\Administrator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:permissions';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Permissions';

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
        $this->signature = config('administration.command_prefix') . ':permissions';

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /*
         * reset cached data
         */
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /** @var Role $administratorsRole */
        $administratorsRole = Role::where('name', config('administration.administrators_role_name'))->first();
        if (!$administratorsRole) {
            $administratorsRole = new Role();
            $administratorsRole->name = config('administration.administrators_role_name');
            $administratorsRole->guard_name = config('administration.guard_name');
            $administratorsRole->save();
        }

        $routes = \Route::getRoutes();

        /** @var Route $route */
        foreach ($routes as $route) {
            $routeName = $route->getName();
            $routeUri = $route->uri();

            if (empty($routeName)
                || !Str::startsWith($routeName, [
                    config('administration.route_name_prefix') . '.',
                ])
            ) {
                $this->warn('Skip/Delete permission: ' . $routeName . ' / uri: ' . $routeUri);

                Permission::where('name', $routeName)->delete();

                continue;
            }

            $this->checkPermissions($routeName);
        }

        /*
         * attach all permissions to admin role
         */
        $administratorsRole->syncPermissions(Permission::all());

        /*
         * attach admin role to first user
         */
        /** @var Administrator $firstUser */
        $firstUser = Administrator::first();
        if ($firstUser) {
            $firstUser->assignRole(config('administration.administrators_role_name'));
        }
    }

    /**
     * Check current permission
     * @param string $key
     * @return void
     */
    private function checkPermissions(string $key): void
    {
        $permission = Permission::where('name', $key)->first();
        if (empty($permission)) {
            Permission::create(['name' => $key, 'guard_name' => config('administration.guard_name')]);
            $this->info('Insert new permission:' . $key);
        }
    }
}
