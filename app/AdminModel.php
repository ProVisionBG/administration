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
     * Guard used in administration.
     *
     * @var string
     */
    public $guard = 'provision_administration';

    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = $this->makeSlugUnique($this->generateSlug($value));
    }
}
