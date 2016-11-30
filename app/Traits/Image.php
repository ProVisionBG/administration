<?php

namespace ProVision\Administration\Traits;


trait Image {
    /**
     * Resize image
     *
     * @param $file
     * @return bool
     */
    function resize($file) {

        if (!\File::exists($file)) {
            return false;
        }

        //_
        \Intervention\Image\Facades\Image::make($file)->fit(100, 100, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file) . '/_' . basename($file));

        $sizes = config('provision_administration.image_sizes');

        if (empty($sizes)) {
            return true;
        }

        foreach ($sizes as $key => $size) {

            //check mode
            if (empty($size['mode']) || !in_array($size['mode'], [
                    'fit',
                    'resize'
                ])
            ) {
                \Debugbar::error('Image resize wrong mode! (key: ' . $key . ')');
                \Log::error('Image resize wrong mode! (key: ' . $key . ')');
                continue;
            }

            //set resize mode
            $mode = $size['mode'];

            //make resize
            \Intervention\Image\Facades\Image::make($file)->$mode($size['width'], $size['height'], function ($c) use ($size) {
                if (!empty($size['aspectRatio']) && $size['aspectRatio'] === true) {
                    $c->aspectRatio();
                }
                if (!empty($size['upsize']) && $size['upsize'] === true) {
                    $c->upsize();
                }
            })->save(dirname($file) . '/' . $key . '_' . basename($file));

        }

        //B_


//        Image::make($file)->fit(400, 300, function ($c) {
//            $c->aspectRatio();
//            $c->upsize();
//        })->save(dirname($file) . '/C_' . basename($file));

        return true;
    }
}
