<?php
namespace ProVision\Administration;

use Illuminate\Database\Eloquent\Model;

class AdminModelTranslations extends Model {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Customize slug engine
     *
     * @param $engine
     * @return mixed
     */
    public function customizeSlugEngine($engine) {
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

?>