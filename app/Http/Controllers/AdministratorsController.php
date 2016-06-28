<?php

namespace ProVision\Administration\Http\Controllers;

use App\Http\Requests;
use Datatables;
use Form;
use Guzzle\Http\Message\Response;
use Kris\LaravelFormBuilder\FormBuilder;
use ProVision\Administration\AdminUser;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Forms\AdministratorForm;
use Request;


class AdministratorsController extends BaseAdministrationController {
    public function __construct() {
        parent::__construct();
        Administration::setModuleName(trans('administration::administrators.administrators'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        if (Request::ajax()) {
            $users = AdminUser::has('roles');

            $datatables = Datatables::of($users)
                ->addColumn('action', function ($user) {
                    $actions = '';
                    if (!empty($user->deleted_at)) {
                        //restore button
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

                    if (Request::has('delete') && Request::input('delete') == 'true') {
                        $query->whereNotNull('deleted_at');
                    }
                });

            return $datatables->make(true);
        }

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::administrators.administrators'), route('provision.administration.administrators.index'));
        });

        return view('administration::administrators.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder) {
        $form = $formBuilder->create(AdministratorForm::class, [
            'method' => 'PUT',
            'url' => route('provision.administration.administrators.store'),
            'role' => 'form',
            'id' => 'formID'
        ]);

        Administration::setModuleName(trans('administration::administrators.create_administrator'));

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
    public function store(Request $request) {
        $form = $this->form(AdministratorForm::class);

        $form->validate(['title' => 'required|alpha_num']);

        if (!$form->isValid()) {
            return redirect()->back()->withErrors($form->getErrors())->withInput();
        }

        Post::create($request->all());
        return redirect()->route('posts');
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

        Administration::setModuleName(trans('administration::administrators.edit_administrator', ['name' => $user->name]));

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $object = AdminUser::withTrashed()->where('id', $id);
        if (empty($object->deleted_at)) {
            $object->delete();
        } else {
            $object->restore();
        }

        return response()->json(['ok'], 200);
    }
}
