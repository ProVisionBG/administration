<?php

namespace ProVision\Administration;


class Media extends AdminModel {

    use \Rutorika\Sortable\SortableTrait;

    protected static $sortableField = 'order_index';

    public $rules = array(
        'key' => 'required|max:25',
        'item_id' => 'required|integer',
        'file' => 'required',
        'lang' => 'max:2|min:2|null'
    );
    public $table = 'media';
    protected $appends = ['path'];
    protected $fillable = [
        'module',
        'sub_module',
        'item_id',
        'lang',
        'titles',
        'type',
        'order_index'
    ];
    protected $casts = [
        'titles' => 'array'
    ];

    public function __construct() {
        parent::__construct();
    }

    public function getPathAttribute() {
        $path = '/uploads/media/' . $this->module;

        if (!empty($this->sub_module)) {
            $path .= '/' . $this->sub_module;
        }

        //item_id
        $path .= '/' . $this->item_id;

        //id
        $path .= '/' . $this->id . '/';

        return $path;
    }
}
