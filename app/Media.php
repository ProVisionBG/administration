<?php

namespace ProVision\Administration;


class Media extends AdminModel {
    use \Rutorika\Sortable\SortableTrait;

    protected static $sortableGroupField = [
        'module',
        'sub_module',
        'item_id'
    ];
    protected static $sortableField = 'order_index';
    public $rules = array(
        'key' => 'required|max:25',
        'item_id' => 'required|integer',
        'file' => 'required',
        'lang' => 'max:2|min:2|null'
    );


    public $table = 'media';
    public $module = 'media';
    protected $appends = ['path'];
    protected $fillable = [
        'module',
        'sub_module',
        'item_id',
        'lang',
        'titles',
        'type',
        'visible'
    ];
    protected $casts = [
        'titles' => 'array',
        'visible' => 'boolean'
    ];

    public function __construct() {
        parent::__construct();
    }

    public static function boot() {

        static::deleting(function ($model) {
            /*
             * automatic remove files
             */
            if (\File::exists(public_path($model->path))) {
                \File::deleteDirectory(public_path($model->path));
            }
        });

        parent::boot();

    }

    public function getPathAttribute() {
        $path = '/uploads/media/' . $this->attributes['module'];

        if (!empty($this->attributes['sub_module'])) {
            $path .= '/' . $this->attributes['sub_module'];
        }

        //item_id
        $path .= '/' . $this->item_id;

        //id
        $path .= '/' . $this->id . '/';

        return $path;
    }

    /*
     * quick resize media item
     */
    public function quickResize() {
        parent::resize(realpath(public_path($this->path . '/' . $this->file)));
    }
}
