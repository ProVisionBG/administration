<?php

namespace ProVision\Administration;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Validator;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class AdminUser extends Authenticatable {
    use EntrustUserTrait {
        EntrustUserTrait::restore insteadof SoftDeletes;
    }
    use SoftDeletes;

    /*
    * validation rules
    */
    public $rules = array(
        'password' => 'min:5|confirmed',
        'email' => 'required|email|unique:users,email',
        'name' => 'required'
    );
    protected $messages = array();
    protected $errors = array();
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $guarded = [
        'password',
        'remember_token'
    ];

    protected $dates = ['deleted_at'];

    public function validate($data) {
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

    public function errors() {
        return $this->errors;
    }
}
