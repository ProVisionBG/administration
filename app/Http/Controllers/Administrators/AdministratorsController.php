<?php

namespace ProVision\Administration\Http\Controllers\Administrators;

use Datatables;
use Form;
use Kris\LaravelFormBuilder\FormBuilder;
use ProVision\Administration\AdminUser;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Forms\AdministratorFilterForm;
use ProVision\Administration\Forms\AdministratorForm;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use Request;


class AdministratorsController extends BaseAdministrationController {
    public function __construct() {
        parent::__construct();
        Administration::setTitle(trans('administration::administrators.administrators'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {

        if (Request::ajax()) {
            $users = AdminUser::select([
                'id',
                'name',
                'email',
                'deleted_at'
            ])
                ->with('roles');

            $datatables = Datatables::of($users)
                ->addColumn('action', function ($user) {
                    $actions = '';
                    if (!empty($user->deleted_at)) {
                        $actions .= Form::adminRestoreButton(trans('administration::index.restore'), route('provision.administration.administrators.destroy', $user->id));
                    } else {
                        if ($user->id != \Auth::guard('provision_administration')->user()->id) {
                            $actions .= Form::adminDeleteButton(trans('administration::index.delete'), route('provision.administration.administrators.destroy', $user->id));
                        }
                    }

                    return Form::adminEditButton(trans('administration::index.edit'), route('provision.administration.administrators.edit', $user->id)) . $actions;
                })
                ->filter(function ($query) {
                    if (Request::has('name')) {
                        $query->where('name', 'like', "%" . Request::get('name') . "%");
                    }

                    if (Request::has('email')) {
                        $query->where('email', 'like', "%" . Request::get('email') . "%");
                    }

                    if (Request::has('deleted') && Request::input('deleted') == 'true') {
                        $query->onlyTrashed();
                    }

                    if (!Request::has('all-users') || Request::input('all-users') != 'true') {
                        $query->has('roles');
                    }
                });

            return $datatables->make(true);
        }

        $filterForm = $this->form(AdministratorFilterForm::class, [
                'method' => 'POST',
                'url' => route('provision.administration.administrators.index')
            ]
        );


        $table = Datatables::getHtmlBuilder()
            ->addColumn([
                'data' => 'id',
                'name' => 'id',
                'title' => trans('administration::administrators.id')
            ])->addColumn([
                'data' => 'name',
                'name' => 'name',
                'title' => trans('administration::administrators.name')
            ])
            ->addColumn([
                'data' => 'email',
                'name' => 'email',
                'title' => trans('administration::administrators.email')
            ]);

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators'), route('provision.administration.administrators.index'));
        });

//        return view('administration::administrators.index');
        return view('administration::empty-listing', compact('table', 'filterForm'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder) {
        $form = $formBuilder->create(AdministratorForm::class, [
                'method' => 'POST',
                'url' => route('provision.administration.administrators.store'),
            ]
//            [
//                'title' => 'Тестова форма',
//                'type' => 'danger'
//            ]
        );


        Administration::setTitle(trans('administration::administrators.create_administrator'));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators'), route('provision.administration.administrators.index'));
            $breadcrumbs->push(trans('administration::administrators.create_administrator'), route('provision.administration.administrators.create'));
        });

        return view('administration::empty-form', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(\Illuminate\Http\Request $request) {

        $adminUser = new AdminUser();

        $requestData = Request::all();

        if ($adminUser->validate($requestData)) {
            $adminUser->fill($requestData);

            /*
            * add roles
            */
            $adminUser->roles()->detach();
            if (!empty(Request::has('roles'))) {
                foreach (Request::input('roles') as $role) {
                    $adminUser->roles()->attach($role);
                }
            }

            $adminUser->save();


            return \Redirect::route('provision.administration.administrators.index');
        } else {
            return \Redirect::route('provision.administration.administrators.create')
                ->withInput()
                ->withErrors($adminUser->errors());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id) {
        $user = AdminUser::withTrashed()->where('id', $id)->first();

        $form = $formBuilder->create(AdministratorForm::class, [
            'method' => 'PUT',
            'url' => route('provision.administration.administrators.update', $id),
            'role' => 'form',
            'id' => 'formID',
            'model' => $user
        ]);

        Administration::setTitle(trans('administration::administrators.edit_administrator', ['name' => $user->name]));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) use ($user) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators'), route('provision.administration.administrators.index'));
            $breadcrumbs->push(trans('administration::administrators.edit_administrator', ['name' => $user->name]), route('provision.administration.administrators.index'));
        });

        return view('administration::empty-form', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $adminUser = AdminUser::findOrFail($id);

        $requestData = Request::all();

        if (!Request::has('password')) {
            unset($adminUser->rules['password']);
            unset($requestData['password']);
        }
        $adminUser->rules['email'] .= ',' . $adminUser->id;

        if ($adminUser->validate($requestData)) {

            /*
             * add roles
             */
            $adminUser->roles()->detach();
            if (!empty(Request::has('roles'))) {
                foreach (Request::input('roles') as $role) {
                    $adminUser->roles()->attach($role);
                }
            }

            $adminUser->fill($requestData);
            $adminUser->save();

            return \Redirect::route('provision.administration.administrators.index');
        } else {
            return \Redirect::route('provision.administration.administrators.edit', [$adminUser->id])
                ->withInput()
                ->withErrors($adminUser->errors());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = AdminUser::withTrashed()->find($id);

        if (!$object->trashed()) {
            $object->delete();
        } else {
            $object->restore();
        }

        return response()->json(['ok'], 200);
    }
}
