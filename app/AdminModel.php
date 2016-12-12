<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Illuminate\Database\Eloquent\Model;
use ProVision\Administration\Traits\ValidationTrait;

class AdminModel extends Model
{
    use ValidationTrait;

    /**
     * Module settings.
     *
     * @var string
     */
    public $module;
    public $sub_module;

    /**
     * Guard used in administration.
     *
     * @var string
     */
    public $guard = 'provision_administration';

    public function __construct()
    {
        parent::__construct();

        /*
         * Проверка за базовите property в модела
         */
        if (empty($this->module)) {
            abort(500, ' Please define property $module in model!');
        }

        if (! property_exists($this, 'sub_module') || is_null($this->sub_module)) {
            $this->sub_module = '';
        }
    }

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $this->makeSlugUnique($this->generateSlug($value));
    }
}
