<?php

namespace ProVision\Administration\Exceptions;

use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends \App\Exceptions\Handler
{

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof NotFoundHttpException) {
            return response()->view('administration::errors.404', compact('exception'), 404);
        }

        return parent::render($request, $exception);
    }

}