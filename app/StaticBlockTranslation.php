<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

class StaticBlockTranslation extends AdminModelTranslations
{
    public $timestamps = false;
    public $table = 'static_blocks_translations';

    protected $fillable = ['text'];

    public function __construct()
    {
        parent::__construct();
    }
}
