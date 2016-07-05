<?php

namespace ProVision\Administration;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;


class StaticBlockTranslation extends AdminModel {
    public $timestamps = false;
    public $table = 'static_blocks_translations';
   
    protected $fillable = ['text'];

    public function __construct() {
        parent::__construct();
    }

}
