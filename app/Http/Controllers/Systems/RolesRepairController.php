<?php

namespace ProVision\Administration\Http\Controllers\Systems;

use Module;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use ProVision\Administration\Permission;
use ProVision\Administration\Role;


class RolesRepairController extends BaseAdministrationController {
    public function __construct() {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        Administration::setTitle(trans('administration::systems.roles-repair'));

        $modules = Module::all();
        $adminRole = Role::where('name', 'admin')->first();

        $responses = [];

        if (empty($modules)) {
            array_push($responses, [
                'status' => 'danger',
                'message' => trans('administration::systems.no-installed-modules')
            ]);
        } else {
            foreach ($modules as $module) {
                $moduleManifest = Module::getManifest($module['slug']);
                if (!empty($moduleManifest['administration']['permissions'])) {
                    foreach ($moduleManifest['administration']['permissions'] as $key => $name) {
                        $permission = Permission::where('name', $key)->first();
                        if (empty($permission)) {
                            $permission = new Permission();
                            $permission->name = $key;
                            $permission->display_name = $name;
                            $permission->save();
                            array_push($responses, [
                                'status' => 'success',
                                'message' => trans('administration::systems.add-new-permissions', [
                                    'module' => $module['name'],
                                    'permission' => $key
                                ])
                            ]);
                        }
                        if (!$adminRole->perms()->where('permission_id', $permission->id)->first()) {
                            array_push($responses, [
                                'status' => 'info',
                                'message' => trans('administration::systems.add-new-permissions-to-admin-role', [
                                    'module' => $module['name'],
                                    'permission' => $key
                                ])
                            ]);

                            $adminRole->attachPermission($permission);
                        }

                    }
                } else {
                    array_push($responses, [
                        'status' => 'warning',
                        'message' => trans('administration::systems.module-not-have-permissions', ['module' => $module['name']])
                    ]);
                }
            }
        }

        array_push($responses, [
            'status' => 'info',
            'message' => trans('administration::systems.roles-repair-end')
        ]);


        return view('administration::systems.roles-repair', compact('responses'));
    }


}
