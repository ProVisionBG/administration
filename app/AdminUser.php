<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ProVision\Administration\Notifications\ResetPassword;

class AdminUser extends Authenticatable
{
    use SoftDeletes, Notifiable, EntrustUserTrait {
        SoftDeletes::restore as sdRestore;
        EntrustUserTrait::restore as euRestore;
    }

    /*
    * validation rules
    */
    public $rules = [
        'password' => 'min:5|confirmed',
        'email' => 'required|email|unique:users,email',
        'name' => 'required',
    ];
    protected $messages = [];
    protected $errors = [];
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
        'remember_token',
    ];

    protected $dates = ['deleted_at'];

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

    /**
     * Send the password reset notification.
     *
     * @param  string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * Fix SoftDeletes::restore & EntrustUserTrait::restore.
     */
    public function restore()
    {
        $this->sdRestore();
        Cache::tags(Config::get('entrust.role_user_table'))->flush();
    }
}
