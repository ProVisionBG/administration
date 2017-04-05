<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Cviebrock\EloquentSluggable\Sluggable;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class StaticBlock extends AdminModel
{
    use Sluggable;
    use Translatable;
    use SoftDeletes;

    public $translatedAttributes = ['text'];
    public $rules = [
        'key' => 'required|max:25',
        'active' => 'boolean'
    ];
    public $table = 'static_blocks';
    public $module = 'administration';
    public $sub_module = 'static_blocks';

    protected $fillable = [
        'key',
        'text',
        'active',
        'note'
    ];
    protected $with = ['translations'];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable()
    {
        return [
            'key' => [
                'source' => 'key',
            ],
        ];
    }
}
