<?php

namespace ProVision\Administration\Http\Controllers\Systems;

use Module;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use ProVision\Administration\Permission;
use ProVision\Administration\Role;


class RolesRepairController extends BaseAdministrationController {

    private $adminRole;
    private $responses = [];

    public function __construct() {
        parent::__construct();
        $this->adminRole = Role::where('name', 'admin')->first();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        Administration::setTitle(trans('administration::systems.roles-repair'));

        $routes = \Route::getRoutes();
        foreach ($routes as $route) {
            if (empty($route->getName())) {
                continue;
            }

            if (preg_match('/(provision\.administration\.([\-a-z1-9]+\.[a-z\-1-9\.]+))/simx', $route->getName(), $regs)) {
                //dd($regs);
                $permission = $regs[2];
                $this->checkPermissions(['name' => 'Administration'], $permission, $permission);
            }
        }

        //additional permissions for access to administration
        $this->checkPermissions(['name' => 'Administration'], 'administration-access', 'administration-access');

        /*
        $modules = Module::all();

        if (empty($modules)) {
            array_push($this->responses, [
                'status' => 'danger',
                'message' => trans('administration::systems.no-installed-modules')
            ]);
        } else {
            foreach ($modules as $module) {
                $moduleManifest = Module::getManifest($module['slug']);
                if (!empty($moduleManifest['administration']['permissions'])) {
                    foreach ($moduleManifest['administration']['permissions'] as $key => $name) {
                        $this->checkPermissions($module, $key, $name);
                    }
                } else {
                    array_push($this->responses, [
                        'status' => 'warning',
                        'message' => trans('administration::systems.module-not-have-permissions', ['module' => $module['name']])
                    ]);
                }
            }
        }
        */
        array_push($this->responses, [
            'status' => 'info',
            'message' => trans('administration::systems.roles-repair-end')
        ]);

        $responses = $this->responses;
        return view('administration::systems.roles-repair', compact('responses'));
    }

    function checkPermissions($module, $key, $name) {

        $permission = Permission::where('name', $key)->first();
        if (empty($permission)) {
            $permission = new Permission();
            $permission->name = $key;
            $permission->display_name = $name;
            $permission->save();
            array_push($this->responses, [
                'status' => 'success',
                'message' => trans('administration::systems.add-new-permissions', [
                    'module' => $module['name'],
                    'permission' => $key
                ])
            ]);
        }

        if (!$this->adminRole->perms()->where('permission_id', $permission->id)->first()) {
            array_push($this->responses, [
                'status' => 'info',
                'message' => trans('administration::systems.add-new-permissions-to-admin-role', [
                    'module' => $module['name'],
                    'permission' => $key
                ])
            ]);

            $this->adminRole->attachPermission($permission);
        }

    }


}
