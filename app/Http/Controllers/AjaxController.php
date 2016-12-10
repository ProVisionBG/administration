<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;

use Illuminate\Support\Facades\DB;
use ProVision\Administration\Http\Requests\AjaxQuickSwichRequest;

class AjaxController extends BaseAdministrationController
{
    /**
     * Автоматично запазване на сортиране в списък.
     * @todo: да се направи и за модели които не ползват NodeTrait
     */
    public function saveOrder()
    {
        if (! empty(\Request::input('data'))) {
            //$debug = [];
            foreach (\Request::input('data') as $index => $item) {
                if (empty($item)) {
                    continue;
                }

                $object = $item['model']::find($item['id']);
                if (empty($object)) {
                    continue;
                }

                if ($item['oldPosition'] > $item['newPosition']) {
                    //$debug[] = 'up:' . ($item['oldPosition'] - $item['newPosition']);
                    $object->up($item['oldPosition'] - $item['newPosition']);
                } else {
                    // $debug[] = 'down:' . ($item['newPosition'] - $item['oldPosition']);
                    $object->down($item['newPosition'] - $item['oldPosition']);
                }

                $object->fixTree();

                break;
            }

            return response()->json([
                'status' => true,
                //'debug' => $debug
            ]);
        }
    }

    /**
     * Автоматично запазване на switch state - за листинг таблица.
     * @param AjaxQuickSwichRequest $request
     * @return mixed
     */
    public function saveQuickSwitch(AjaxQuickSwichRequest $request)
    {
        if ($request->state == 'true') {
            $request->state = 1;
        } else {
            $request->state = 0;
        }

        return DB::table($request->table)
            ->where('id', $request->id)
            ->update([
                $request->field => $request->state,
            ]);
    }
}
