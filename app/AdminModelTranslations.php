<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Illuminate\Database\Eloquent\Model;

class AdminModelTranslations extends Model
{
    /**
     * Guard used in administration.
     *
     * @var string
     */
    public $guard = 'provision_administration';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Customize slug engine.
     *
     * @param $engine
     * @return mixed
     */
    public function customizeSlugEngine($engine)
    {
        /*
         * @todo: да го добавя в config
         */
        $engine->addRule('ъ', 'a');
        $engine->addRule('щ', 'sht');
        $engine->addRule('ь', 'y');
        $engine->addRule('Ъ', 'A');
        $engine->addRule('Щ', 'SHT');

        return $engine;
    }
}
