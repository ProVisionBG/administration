<?php
/**
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Library;

use Exception;
use Illuminate\Support\Facades\Blade;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

class CustomBladeCompiler
{
    public static function render($string, $data = [])
    {
        $php = Blade::compileString($string);

        $obLevel = ob_get_level();
        ob_start();
        extract($data, EXTR_SKIP);

        try {
            eval('?' . '>' . $php);
        } catch (Exception $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw $e;
        } catch (Throwable $e) {
            while (ob_get_level() > $obLevel) ob_end_clean();
            throw new FatalThrowableError($e);
        }

        return ob_get_clean();
    }
}