<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers\Administrators;

use Form;
use Kris\LaravelFormBuilder\FormBuilder;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Forms\RolesForm;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use ProVision\Administration\Role;
use Request;
use Yajra\DataTables\Facades\DataTables;

class AdministratorsRolesController extends BaseAdministrationController {
    public function __construct() {
        parent::__construct();
        Administration::setTitle(trans('administration::administrators.administrators-roles'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Request::ajax()) {
            $roles = Role::all();

            $datatables = Datatables::of($roles)
                ->addColumn('action', function ($role) {
                    $actions = '';
                    if (!empty($role->deleted_at)) {
                        //restore button
                    } else {
                        if ($role->id != \Auth::guard(config('provision_administration.guard'))->user()->id) {
                            $actions .= Form::adminDeleteButton(trans('administration::index.delete'), route('provision.administration.administrators-roles.destroy', $role->id));
                        }
                    }

                    return Form::adminEditButton(trans('administration::index.edit'), route('provision.administration.administrators-roles.edit', $role->id)) . $actions;
                })
                ->filter(function ($query) {
                    //                    if (Request::has('name')) {
//                        $query->where('name', 'like', "%" . Request::get('name') . "%");
//                    }
//
//                    if (Request::has('email')) {
//                        $query->where('email', 'like', "%" . Request::get('email') . "%");
//                    }
//
//                    if (Request::has('delete') && Request::input('delete') == 'true') {
//                        $query->whereNotNull('deleted_at');
//                    }
                });

            return $datatables->make(true);
        }

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators-roles'), route('provision.administration.administrators-roles.index'));
        });

        $table = Datatables::getHtmlBuilder()
            ->addColumn([
                'data' => 'id',
                'name' => 'id',
                'title' => trans('administration::administrators.id'),
            ])->addColumn([
                'data' => 'display_name',
                'name' => 'display_name',
                'title' => trans('administration::administrators.group_name'),
            ])->addColumn([
                'data' => 'name',
                'name' => 'name',
                'title' => trans('administration::administrators.group_key'),
            ]);

        return view('administration::empty-listing', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder) {
        $form = $formBuilder->create(RolesForm::class, [
                'method' => 'POST',
                'url' => route('provision.administration.administrators-roles.store'),
            ]
//            [
//                'title' => 'Тестова форма',
//                'type' => 'danger'
//            ]
        );

        Administration::setTitle(trans('administration::administrators.create_role'));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators'), route('provision.administration.administrators-roles.index'));
            $breadcrumbs->push(trans('administration::administrators.create_role'), route('provision.administration.administrators-roles.create'));
        });

        return view('administration::empty-form', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request) {
        $role = new Role();

        $requestData = Request::all();

        if ($role->validate($requestData)) {
            $role->fill($requestData);
            $role->save();

            /*
            * add permissions
            */
            if (!empty(Request::has('permissions'))) {
                $rolesData = Request::input('permissions');
                foreach ($rolesData as $roleRow => $value) {
                    if (empty($value)) {
                        unset($rolesData[$roleRow]);
                    }
                }

                if (count($rolesData) > 0) {
                    $role->perms()->sync($rolesData);
                }
            }

            return \Redirect::route('provision.administration.administrators-roles.index');
        } else {
            return \Redirect::route('provision.administration.administrators-roles.create')
                ->withInput()
                ->withErrors($role->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param FormBuilder $formBuilder
     * @param  int        $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id) {
        $role = Role::where('id', $id)->first();

        $form = $formBuilder->create(RolesForm::class, [
            'method' => 'PUT',
            'url' => route('provision.administration.administrators-roles.update', $id),
            'role' => 'form',
            'id' => 'formID',
            'model' => $role,
        ]);

        Administration::setTitle(trans('administration::administrators.edit_role', ['name' => $role->name]));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) use ($role) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators-roles'), route('provision.administration.administrators-roles.index'));
            $breadcrumbs->push(trans('administration::administrators.edit_role', ['name' => $role->name]), route('provision.administration.administrators-roles.index'));
        });

        $js_scripts = '
        <script>
        $(function(){
            $("#select-all-permissions").click(function(){
                $.each($(\'#permissions-list input\'), function() {   
                    $("#"+$(this).attr("id")).bootstrapSwitch(\'state\', true, true);
                });
            });           
        });
        </script>
        ';

        return view('administration::empty-form', compact('form', 'js_scripts'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);

        $requestData = Request::all();


        if ($role->validate($requestData)) {
            $role->fill($requestData);
            $role->save();

            /*
             * add permissions
             */
            if (!empty(Request::has('permissions'))) {

                $rolesData = Request::input('permissions');
                foreach ($rolesData as $roleRow => $value) {
                    if (empty($value)) {
                        unset($rolesData[$roleRow]);
                    }
                }

                if (count($rolesData) > 0) {
                    $role->perms()->sync($rolesData);
                }
            }

            return \Redirect::route('provision.administration.administrators-roles.index');
        } else {
            return \Redirect::route('provision.administration.administrators-roles.edit', [$role->id])
                ->withInput()
                ->withErrors($role->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = Role::where('id', $id);
        if (empty($object->deleted_at)) {
            $object->forceDelete();
        } else {
            //$object->restore();
        }

        return response()->json(['ok'], 200);
    }
}
