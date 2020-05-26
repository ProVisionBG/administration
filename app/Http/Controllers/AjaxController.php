<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;

use Illuminate\Http\Request;
use ProVision\Administration\Http\Requests\AjaxQuickSwichRequest;

class AjaxController extends BaseAdministrationController {
    /**
     * Автоматично запазване на сортиране в списък.
     *
     * @todo: да се направи и за модели които не ползват NodeTrait
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveOrder(Request $request) {
        if (!empty($request->data)) {

            $data = collect($request->data);
            $model = $data->first()['model'];

            //зарежда всички обекти за промяна
            $changedObjects = $model::whereIn('id', $data->pluck('id'))->get();

            if (!$changedObjects) {
                return response()->json([
                    'status' => false,
                    'message' => 'Не са намерени обекти за сортиране'
                    //'debug' => $debug
                ], 422);
            }

            $debug = [];
            foreach ($data as $item) {
                $objectToChange = $model::where('id', $item['id'])->first();
                if (!$objectToChange) {
                    $debug[] = '$objectToChange  not found';
                    continue;
                }

                $objectFromChange = $changedObjects->where('id', $data->where('oldPosition', $item['newPosition'])->first()['id'])->first();
                if (!$objectFromChange) {
                    $debug[] = '$objectFromChange not found';
                    continue;
                }

                $debug[] = [
                    'old' => [
                        'id' => $objectToChange->id,
                        'parent_id' => $objectToChange->parent_id,
                        '_lft' => $objectToChange->_lft,
                        '_rgt' => $objectToChange->_rgt,
                    ],
                    'new' => [
                        'id' => $objectFromChange->id,
                        'parent_id' => $objectFromChange->parent_id,
                        '_lft' => $objectFromChange->_lft,
                        '_rgt' => $objectFromChange->_rgt,
                    ]
                ];

                /*
                 * Save changes
                 */
                $objectToChange->parent_id = $objectFromChange->parent_id;
                $objectToChange->_lft = $objectFromChange->_lft;
                $objectToChange->_rgt = $objectFromChange->_rgt;
                $objectToChange->save();
            }

            $model::fixTree();

            return response()->json([
                'status' => true,
                'debug' => $debug
            ]);
        }
    }

    /**
     * Автоматично запазване на switch state - за листинг таблица.
     *
     * @param AjaxQuickSwichRequest $request
     *
     * @return mixed
     */
    public function saveQuickSwitch(AjaxQuickSwichRequest $request) {
        if ($request->state == 'true' || $request->state == '1' || $request->state == 1) {
            $request->state = 1;
        } else {
            $request->state = 0;
        }

        $object = new $request->class;
        $object = $object->where('id', $request->id)->first();

        if (empty($object)) {
            return response()->json(['model not found'], 404);
        }

        $object->setAttribute($request->field, $request->state);
        $object->save();

        return response()->json(['updated_rows' => 1]);
    }
}
