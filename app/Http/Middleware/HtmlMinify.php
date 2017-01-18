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

            //convert encoding to utf8
            $output = mb_convert_encoding($output, 'utf8');

            if (strpos($output, '<pre>') !== false) {
                $replace = array(
                    '/<!--[^\[](.*?)[^\]]-->/s' => '',
                    "/<\?php/" => '<?php ',
                    "/\r/" => '',
                    "/>\n</" => '><',
                    "/>\s+\n</" => '><',
                    "/>\n\s+</" => '><'
                );
            } else {
                $replace = array(
                    '/<!--[^\[](.*?)[^\]]-->/s' => '',
                    "/<\?php/" => '<?php ',
                    "/\n([\S])/" => '$1',
                    "/\r/" => '',
                    "/\n/" => '',
                    "/\t/" => '',
                    "/ +/" => ' '
                );
            }

            // Remove htmlcomment;
            $additionaly = array(
                // strip whitespaces after tags, except space
                '/\>[^\S ]+/s' => '>',
                // strip whitespaces before tags, except space
                '/[^\S ]+\</s' => '<',
                // shorten multiple whitespace sequences
                '/(\s)+/s' => '\\1',
                // Remove htmlcomment
                '!/\*.*?\*/!s' => '',
                '/\n\s*\n/' => ''
            );

            $output = preg_replace(array_keys($replace), array_values($replace), $output);
            $output = preg_replace(array_keys($additionaly), array_values($additionaly), $output);
            $output = $output = $this->compress($output);

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

    function compress($buffer)
    {
        /**
         * To remove useless whitespace from generated HTML, except for Javascript.
         * [Regex Source]
         * https://github.com/bcit-ci/codeigniter/wiki/compress-html-output
         * http://stackoverflow.com/questions/5312349/minifying-final-html-output-using-regular-expressions-with-codeigniter
         */
        $regexRemoveWhiteSpace = '%# Collapse ws everywhere but in blacklisted elements.
        (?>             # Match all whitespaces other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          (?:           # Begin (unnecessary) group.
            (?:         # Zero or more of...
              [^<]++    # Either one or more non-"<"
            | <         # or a < starting a non-blacklist tag.
              (?!/?(?:textarea|pre)\b)
            )*+         # (This could be "unroll-the-loop"ified.)
          )             # End (unnecessary) group.
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %ix';

        $regexRemoveWhiteSpace = '%(?>[^\S ]\s*| \s{2,})(?=(?:(?:[^<]++| <(?!/?(?:textarea|pre)\b))*+)(?:<(?>textarea|pre)\b|\z))%ix';
        $re = '%# Collapse whitespace everywhere but in blacklisted elements.
        (?>             # Match all whitespans other than single space.
          [^\S ]\s*     # Either one [\t\r\n\f\v] and zero or more ws,
        | \s{2,}        # or two or more consecutive-any-whitespace.
        ) # Note: The remaining regex consumes no text at all...
        (?=             # Ensure we are not in a blacklist tag.
          [^<]*+        # Either zero or more non-"<" {normal*}
          (?:           # Begin {(special normal*)*} construct
            <           # or a < starting a non-blacklist tag.
            (?!/?(?:textarea|pre|script)\b)
            [^<]*+      # more non-"<" {normal*}
          )*+           # Finish "unrolling-the-loop"
          (?:           # Begin alternation group.
            <           # Either a blacklist start tag.
            (?>textarea|pre|script)\b
          | \z          # or end of file.
          )             # End alternation group.
        )  # If we made it here, we are not in a blacklist tag.
        %Six';

        // $new_buffer = preg_replace('/<!--(.*|\n)-->/Uis', " ", sanitize_output($buffer));
        // $new_buffer = preg_replace('/\s+/', " ", sanitize_output($new_buffer));
        $new_buffer = mb_ereg_replace($regexRemoveWhiteSpace, " ", $this->sanitize_output($buffer));

        // We are going to check if processing has working
        if ($new_buffer === null) {
            $new_buffer = $buffer;
        }

        return $new_buffer;
    }

    function sanitize_output($buffer)
    {
        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s', // shorten multiple whitespace sequences
            '!/\*.*?\*/!s', // Remove htmlcomment
            '/\n\s*\n/'
        ); // Remove htmlcomment

        $replace = array(
            '>',
            '<',
            '\\1',
            '',
            ''
        );
        $buffer = preg_replace($search, $replace, $buffer);
        return $buffer;
    }
}
