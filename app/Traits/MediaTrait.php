<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Traits;

use ProVision\Administration\Media;

trait MediaTrait
{
    /**
     * boot trait.
     */
    public static function bootMediaTrait()
    {
        static::deleting(function ($model) {
            /*
             * Ако модела не използва Soft Deleting изтриваме прикачените към него файлове!
             */
            $traits = class_uses($model);

            if (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', $traits)) {
                // Model uses soft deletes - NOT DELETE ATTACHED FILES
            } else {
                $q = Media::where('module', $model->module)
                    ->where('item_id', $model->id);

                if (! empty($model->sub_module)) {
                    $q->where('sub_module', $model->sub_module);
                } else {
                    $q->where('sub_module', '=', '');
                }

                $mediaItems = $q->get();

                if (! $mediaItems->isEmpty()) {
                    foreach ($mediaItems as $mediaItem) {
                        /*
                         * Изтриваме ги 1 по 1 за да може да изтрие и физически файла със boot()::deleting
                         */
                        $mediaItem->delete();
                    }
                }
            }
        });
    }

    /**
     * Resize image.
     *
     * @param $file
     * @return bool
     */
    public function resize($file)
    {
        if (! \File::exists($file)) {
            return false;
        }

        //_
        \Intervention\Image\Facades\Image::make($file)->fit(100, 100, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file).'/_'.basename($file));

        $sizes = config('provision_administration.image_sizes');

        if (empty($sizes)) {
            return true;
        }

        foreach ($sizes as $key => $size) {

            //check mode
            if (empty($size['mode']) || ! in_array($size['mode'], [
                    'fit',
                    'resize',
                ])
            ) {
                \Debugbar::error('Image resize wrong mode! (key: '.$key.')');
                \Log::error('Image resize wrong mode! (key: '.$key.')');
                continue;
            }

            //set resize mode
            $mode = $size['mode'];

            //make resize
            \Intervention\Image\Facades\Image::make($file)->$mode($size['width'], $size['height'], function ($c) use ($size) {
                if (! empty($size['aspectRatio']) && $size['aspectRatio'] === true) {
                    $c->aspectRatio();
                }
                if (! empty($size['upsize']) && $size['upsize'] === true) {
                    $c->upsize();
                }
            })->save(dirname($file).'/'.$key.'_'.basename($file));
        }

        return true;
    }

    /**
     * Media relation.
     *
     * @return mixed
     */
    public function media()
    {
        $relation = $this->hasMany(Media::class, 'item_id', 'id')
            ->where('module', $this->module)
            ->where('visible', 1)
            ->orderBy('order_index', 'asc');

        if ($this->sub_module != null) {
            $relation->where('sub_module', $this->sub_module);
        }

        return $relation;
    }
}
