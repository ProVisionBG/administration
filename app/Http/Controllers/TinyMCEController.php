<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;


use Illuminate\Http\Request;

class TinyMCEController extends BaseAdministrationController
{
    public function proxy(Request $request)
    {

        $validMimeTypes = array("image/gif", "image/jpeg", "image/png");

        if (empty($request->input('url'))) {
            abort(500, 'Url parameter missing or empty.');
        }

        $scheme = parse_url($request->input('url'), PHP_URL_SCHEME);
        if ($scheme === false || in_array($scheme, array("http", "https")) === false) {
//            header("HTTP/1.0 500 Invalid protocol.");
//            return;
            abort(500, 'Invalid protocol.');
        }

        $content = file_get_contents($_GET["url"]);
        $info = getimagesizefromstring($content);

        if ($info === false || in_array($info["mime"], $validMimeTypes) === false) {
            header("HTTP/1.0 500 Url doesn't seem to be a valid image.");
            abort(500, 'Url doesn\'t seem to be a valid image.');
        }

        $response = \Response::make($content, 200);
        $response->header('Content-Type', $info["mime"]);
        return $response;
    }
}
