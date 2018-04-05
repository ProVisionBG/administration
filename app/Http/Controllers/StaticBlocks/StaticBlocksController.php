<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers\StaticBlocks;

use Form;
use Guzzle\Http\Message\Response;
use Illuminate\Http\Request;
use Kris\LaravelFormBuilder\FormBuilder;
use ProVision\Administration\Facades\Administration;
use ProVision\Administration\Forms\StaticBlockForm;
use ProVision\Administration\Http\Controllers\BaseAdministrationController;
use ProVision\Administration\StaticBlock;
use Yajra\DataTables\Facades\DataTables;

class StaticBlocksController extends BaseAdministrationController {
    public function __construct() {
        parent::__construct();
        Administration::setTitle(trans('administration::static_blocks.name'));
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Request $request) {
        if ($request->ajax()) {
            $staticBlocks = StaticBlock::query();

            $datatables = Datatables::of($staticBlocks)
                ->addColumn('action', function ($staticBlock) {
                    $actions = '';
                    if (!empty($staticBlock->deleted_at)) {
                        //restore button
                    } else {
                        $actions .= Form::adminDeleteButton(trans('administration::index.delete'), route('provision.administration.static-blocks.destroy', $staticBlock->id));
                    }

                    $actions .= Form::mediaManager($staticBlock);

                    return Form::adminEditButton(trans('administration::index.edit'), route('provision.administration.static-blocks.edit', $staticBlock->id)) . $actions;
                })
                ->addColumn('visible', function ($staticBlock) {
                    return Form::adminSwitchButton('active', $staticBlock);
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('key')) {
                        $query->where('key', 'like', '%' . $request->get('key') . '%');
                    }

                    if ($request->has('text')) {
                        $query->where('text', 'like', '%' . $request->get('text') . '%');
                    }

                    if ($request->has('delete') && $request->input('delete') == 'true') {
                        $query->whereNotNull('deleted_at');
                    }
                });

            return $datatables->make(true);
        }

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::static_blocks.name'), route('provision.administration.static-blocks.index'));
        });

        $table = Datatables::getHtmlBuilder()
            ->addColumn([
                'data' => 'id',
                'name' => 'id',
                'title' => trans('administration::administrators.id'),
            ])->addColumn([
                'data' => 'key',
                'name' => 'key',
                'title' => trans('administration::static_blocks.key'),
            ])->addColumn([
                'data' => 'note',
                'name' => 'note',
                'title' => trans('administration::static_blocks.note'),
            ])
            ->addColumn([
                'data' => 'visible',
                'name' => 'visible',
                'title' => trans('administration::static_blocks.visible'),
            ]);

        return view('administration::empty-listing', compact('table'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(FormBuilder $formBuilder) {
        $form = $formBuilder->create(StaticBlockForm::class, [
                'method' => 'POST',
                'url' => route('provision.administration.static-blocks.store'),
            ]
        );

        Administration::setTitle(trans('administration::static_blocks.create'));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::static_blocks.name'), route('provision.administration.static-blocks.index'));
            $breadcrumbs->push(trans('administration::static_blocks.create'), route('provision.administration.static-blocks.create'));
        });

        return view('administration::empty-form', compact('form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $staticBlock = new StaticBlock();

        $requestData = $request->all();

        if ($staticBlock->validate($requestData)) {
            $staticBlock->fill($requestData);
            $staticBlock->save();

            return \Redirect::route('provision.administration.static-blocks.index');
        } else {
            return \Redirect::route('provision.administration.static-blocks.create')
                ->withInput()
                ->withErrors($staticBlock->errors());
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
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(FormBuilder $formBuilder, $id) {
        $staticBlock = StaticBlock::where('id', $id)->withTrashed()->first();

        $form = $formBuilder->create(StaticBlockForm::class, [
            'method' => 'PUT',
            'url' => route('provision.administration.static-blocks.update', $id),
            'role' => 'form',
            'id' => 'formID',
            'model' => $staticBlock,
        ]);

        Administration::setTitle(trans('administration::static_blocks.edit', ['key' => $staticBlock->key]));

        \Breadcrumbs::register('admin_final', function ($breadcrumbs) use ($staticBlock) {
            $breadcrumbs->parent('admin_home');
            $breadcrumbs->push(trans('administration::static_blocks.name'), route('provision.administration.static-blocks.index'));
            $breadcrumbs->push(trans('administration::static_blocks.edit', ['key' => $staticBlock->key]), route('provision.administration.static-blocks.index'));
        });

        return view('administration::empty-form', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  int     $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $staticBlock = StaticBlock::findOrFail($id);

        $requestData = $request->all();

        if ($staticBlock->validate($requestData)) {
            $staticBlock->fill($requestData);
            $staticBlock->save();

            return \Redirect::route('provision.administration.static-blocks.index');
        } else {
            return \Redirect::route('provision.administration.static-blocks.edit', [$staticBlock->id])
                ->withInput()
                ->withErrors($staticBlock->errors());
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
        $object = StaticBlock::where('id', $id);
        if (empty($object->deleted_at)) {
            $object->delete();
        } else {
            $object->restore();
        }

        return response()->json(['ok'], 200);
    }
}
