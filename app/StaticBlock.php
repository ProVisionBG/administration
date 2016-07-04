<?php

namespace ProVision\Administration;

use Cviebrock\EloquentSluggable\Sluggable;
use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;


class StaticBlock extends AdminModel {
    use Sluggable;
    use Translatable;
    use SoftDeletes;

    public $translatedAttributes = ['text'];
    public $rules = array(
        'key' => 'required|max:25',
        'active' => 'boolean'
    );
    public $table = 'static_blocks';
    protected $fillable = [
        'key',
        'text',
        'active'
    ];
    protected $with = ['translations'];

    protected $casts = [
        'active' => 'boolean'
    ];

    public function __construct() {
        parent::__construct();
    }

    /**
     * Return the sluggable configuration array for this model.
     *
     * @return array
     */
    public function sluggable() {
        return [
            'key'
        ];
    }

}
