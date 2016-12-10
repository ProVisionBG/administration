<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Validator;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public $rules = [
        'name' => 'required',
        'display_name' => 'required',
    ];
    protected $messages = [];
    protected $errors = [];

    protected $fillable = [
        'name',
        'display_name',
    ];

    public function validate($data)
    {
        // make a new validator object
        $v = Validator::make($data, $this->rules, $this->messages);

        // check for failure
        if ($v->fails()) {
            // set errors and return false
            $this->errors = $v->errors();

            return false;
        }

        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }
}
