<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;

use Response;
use Illuminate\Http\Request;
use ProVision\Administration\Media;
use App\Http\Controllers\Controller;

class MediaController extends BaseAdministrationController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (! $request->has('itemId')) {
            return Response::json(['Invalid item_id'], 422);
        }

        if (! $request->has('moduleName')) {
            return Response::json(['Invalid module'], 422);
        }

        $mediaQuery = Media::where('item_id', $request->input('itemId'))
            ->where('module', $request->input('moduleName'));

        if ($request->has('moduleSubName')) {
            $mediaQuery->where('sub_module', $request->input('moduleSubName'));
        } else {
            $mediaQuery->where('sub_module', '=', '');
        }

        $items = $mediaQuery->sorted()->get();

        return view('administration::media.items', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $file = $request->file('file');

        if (! $file) {
            return Response::json(['not selected file'], 422);
        }

        if (! $file->isValid()) {
            return Response::json(['Invalid file'], 422);
        }

        $media = new Media();

        if ($request->has('itemId')) {
            $media->item_id = $request->input('itemId');
        } else {
            return Response::json(['Invalid item_id'], 422);
        }

        if ($request->has('moduleName')) {
            $media->setModuleAttribute($request->input('moduleName'));
        } else {
            return Response::json(['Invalid module'], 422);
        }

        if ($request->has('moduleSubName') && ! empty($request->input('moduleSubName'))) {
            $media->setSubModuleAttribute($request->input('moduleSubName'));
        } else {
            $media->setSubModuleAttribute('');
        }

        $media->save();

        $fullPath = public_path().'/uploads/media/'.$media->module.'/';
        if ($request->has('moduleSubName')) {
            $fullPath .= '/'.$media->sub_module.'/';
        }
        $fullPath .= '/'.$media->item_id.'/'.$media->id.'/';

        $extension = $file->getClientOriginalExtension();
        $newFileName = md5($file->getFilename().time());

        $file->move($fullPath, $newFileName.'.'.$extension);

        $media->file = $newFileName.'.'.$extension;
        $media->save();

        $media->resize($fullPath.$media->file);

        return view('administration::media.item', ['item' => $media]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->has('type')) {
            if ($request->input('type') == 'sort') {
                /*
                 * save sort
                 */
                $media = Media::findOrFail($id);

                if ($request->has('before_id')) {
                    $next = Media::findOrFail($request->input('before_id'));
                    $media->moveBefore($next);
                } else {
                    $prev = Media::orderBy('order_index', 'desc')
                        ->where('module', $media->module)
                        ->where('sub_module', $media->sub_module)
                        ->where('item_id', $media->item_id)->first();
                    $media->moveAfter($prev);
                }

                return Response::json(['ok'], 200);
            } elseif ($request->input('type') == 'choice-lang') {
                /*
                 * choice lang
                 */
                $media = Media::findOrFail($id);
                if ($request->has('lang')) {
                    $media->lang = $request->input('lang');
                } else {
                    $media->lang = null;
                }
                $media->save();

                return Response::json(['ok'], 200);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (\Request::has('checked')) {
            foreach (\Request::input('checked') as $id) {
                $media = Media::findOrFail($id);
                $media->delete();
            }

            return \Response::json(\Request::input('checked'), 200);
        }

        $media = Media::findOrFail($id);
        $media->delete();

        return \Response::json(['ok'], 200);
    }
}
