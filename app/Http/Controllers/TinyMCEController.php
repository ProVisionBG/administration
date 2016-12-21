<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;

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

    public function upload(Request $request)
    {

        // getting all of the post data
        $file = array('file' => Input::file('file'));

        // doing the validation, passing post data, rules and the messages
        $validator = Validator::make($file, ['file' => 'required|image']);
        if ($validator->fails()) {
            abort(500, 'Server Error');
        }

        // checking file is valid.
        if (Input::file('file')->isValid()) {
            $destinationPath = public_path('uploads/tinymce/'); // upload path
            $extension = Input::file('file')->getClientOriginalExtension(); // getting image extension
            $fileName = rand(11111, 99999) . '.' . $extension; // renameing image
            Input::file('file')->move($destinationPath, $fileName); // uploading file to given path
            // sending back with message
            return response()->json(['location' => '/uploads/tinymce/' . $fileName]);
        } else {
            abort(500, 'Server Error');
        }

//        reset($_FILES);
//        $temp = current($_FILES);
//        if (is_uploaded_file($temp['tmp_name'])) {
//
//            // Sanitize input
//            if (preg_match("/([^\w\s\d\-_~,;:\[\]\(\).])|([\.]{2,})/", $temp['name'])) {
//                abort(500, 'Invalid file name');
//            }
//
//            // Verify extension
//            if (!in_array(strtolower(pathinfo($temp['name'], PATHINFO_EXTENSION)), array("gif", "jpg", "png"))) {
//                abort('500', 'Invalid extension.');
//            }
//
//            // Accept upload if there was no origin, or if it is an accepted origin
//            $filetowrite = public_path('uploads/tinymce/') . $temp['name'];
//
//            if (!File::exists(basename($filetowrite))) {
//                File::makeDirectory(basename($filetowrite));
//            }
//
//            move_uploaded_file($temp['tmp_name'], $filetowrite);
//
//            return response()->json(['location' => $filetowrite]);
//        } else {
//            abort(500, 'Server Error');
//        }
    }
}
