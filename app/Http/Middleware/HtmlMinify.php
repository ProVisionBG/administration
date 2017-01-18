<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Http\Middleware;

use Closure;
use File;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Response;
use ProVision\Administration\Administration;

class HtmlMinify
{
    /**
     * The application implementation.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if ($this->isAResponseObject($response) && $this->isAnHtmlResponse($response) && !Administration::routeInAdministration()) {
            $output = $response->getContent();

            /* remove comments */
            $output = preg_replace('%/\*(?:(?!\*/|/\*).)*+(?:/\*(?:(?!\*/|/\*).)*+\*/(?:(?!\*/|/\*).)*+)*+.*?\*/%s', '', $output);
            $output = preg_replace('/<!--.*?-->/simx', '', $output);
            /* remove tabs, spaces, newlines, etc. */
            $output = str_replace(array(
                "\r\n",
                "\r",
                "\n",
                "\t",
                '     ',
                '    '
            ), '', $output);

            $output = trim($output);

            $output .= "\n\n" . '<!--
			
	  _____       __      ___     _               _           
	 |  __ \      \ \    / (_)   (_)             | |          
	 | |__) | __ __\ \  / / _ ___ _  ___  _ __   | |__   __ _ 
	 |  ___/ \'__/ _ \ \/ / | / __| |/ _ \| \'_ \  | \'_ \ / _` |
	 | |   | | | (_) \  /  | \__ \ | (_) | | | |_| |_) | (_| |
	 |_|   |_|  \___/ \/   |_|___/_|\___/|_| |_(_)_.__/ \__, |
	                                                     __/ |
	                                                    |___/ 		

	This page generated with ProVision Administration 

	web: http://www.ProVision.bg/
	github: https://github.com/ProVisionBG
	
-->';

            $response->setContent($output);
        }

        return $response;
    }

    /**
     * Check if the response is a usable response class.
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isAResponseObject($response)
    {
        return is_object($response) && $response instanceof Response;
    }

    /**
     * Check if the content type header is html.
     *
     * @param \Illuminate\Http\Response $response
     *
     * @return bool
     */
    protected function isAnHtmlResponse(Response $response)
    {
        $type = $response->headers->get('Content-Type');
        return strtolower(strtok($type, ';')) === 'text/html';
    }
}
