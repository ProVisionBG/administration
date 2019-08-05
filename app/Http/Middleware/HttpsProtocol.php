<?php
/**
 * Copyright (c) 2016. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Http\Middleware;

use Closure;

class HttpsProtocol
{

    public function handle($request, Closure $next)
    {
        /*
         * Проверка дали е пусната настройката за SSL
         */
        if (\Settings::get('ssl_enable') != true) {
            return $next($request);
        }

        if (!$request->secure() && \App::environment('production')) {
            return redirect()->secure($request->getRequestUri(), 301);
        }

        return $next($request);
    }
}