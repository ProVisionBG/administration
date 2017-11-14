<?php
/**
 * Copyright (c) 2016. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Http\Middleware;

use Closure;

class NonWww
{

    public function handle($request, Closure $next)
    {
        /*
         * Проверка дали е пусната настройката за non-WWW
         */
        if (!\Settings::get('non_www_enable')) {
            return $next($request);
        }

        if (substr($request->server('HTTP_HOST'), 0, 4) == 'www.' && \App::environment('production')) {
            if (\Settings::get('ssl_enable')) {
                return redirect()->secure(str_ireplace('www.', '', $request->fullUrl()));
            }
            return redirect(str_ireplace('www.', '', $request->fullUrl()));
        }

        return $next($request);
    }
}