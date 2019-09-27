<?php
/**
 * Copyright (c) 2019. ProVision Media Group Ltd. <http://provision.bg>
 * Venelin Iliev <http://veneliniliev.com>
 */

namespace ProVision\Administration\Models;

use Illuminate\Config\Repository;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

/**
 * Class Administrator
 * @property string name
 * @property string password
 * @package ProVision\Administration\Models
 */
class Administrator extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * @var Repository|mixed|string
     */
    protected $guard_name = 'admin';

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

    /**
     * Administrator constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        /*
         * Set guard name for laravel-permission
         */
        $this->guard_name = config('administration.guard_name');
    }
}
