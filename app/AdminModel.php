<?php
namespace ProVision\Administration;

use Illuminate\Database\Eloquent\Model;
use Image;
use Validator;

class AdminModel extends Model {

    public $errors;

    protected $appends = [
        'module',
        'sub_module'
    ];

    /*
     * Сетване на NULL стойности на празните полета
     *
     *  @author: Venelin Iliev <venelin@provision.bg> / www.ProVision.bg
     *  @datetime: 15.01.2016 г.
     */
    protected $rules = array();

    /*
     * validation rules
     */
    protected $messages = array();

    /*
     * error messages
     */

    public function __construct() {
        parent::__construct();

        /*
         * Проверка за базовите property в модела
         */
        if (empty($this->module)) {
            abort(500, ' Please define property $module in model!');
        }

        if (!property_exists($this, 'sub_module') || is_null($this->sub_module) || !isset($this->attributes['sub_module']) || is_null($this->attributes['sub_module'])) {
            $this->sub_module = '';
//            $this->attributes['sub_module'] = '';
//            abort(500, 'Please define property $sub_module in model! (NOT NULL)');
        }
    }

    public static function boot() {
        parent::boot();

        static::saving(function ($model) {

            /*
             * set null values in db
             */
            foreach ($model->attributes as $key => $value) {
                if ($value === null) {
                    if (array_key_exists($key, $model->attributes)) {
                        $model->attributes[$key] = null;
                    } else {
                        $model->{$key} = null;
                    }
                } elseif (is_string($value)) {
                    //$model->{$key} = trim($value);
                }
            }
        });

        static::deleting(function ($model) {

            /*
             * Ако модела не използва Soft Deleting изтриваме прикачените към него файлове!
             */
            $traits = class_uses($model);

            if (in_array('Illuminate\\Database\\Eloquent\\SoftDeletes', $traits)) {
                // Model uses soft deletes - NOT DELETE ATTACHED FILES
            } else {
                $q = Media::where('module', $model->module)
                    ->where('item_id', $model->id);

                if (!empty($model->sub_module)) {
                    $q->where('sub_module', $model->sub_module);
                } else {
                    $q->where('sub_module', '=', '');
                }

                $mediaItems = $q->get();

                if (!$mediaItems->isEmpty()) {
                    foreach ($mediaItems as $mediaItem) {
                        /*
                         * Изтриваме ги 1 по 1 за да може да изтрие и физически файла със boot()::deleting
                         */
                        $mediaItem->delete();
                    }
                }
            }


        });
    }

    public function getModuleAttribute() {
        if (!isset($this->attributes['module'])) {
            return $this->module;
        }
        return $this->attributes['module'];
    }

    public function getSubModuleAttribute() {
        if (!isset($this->attributes['sub_module'])) {
            return $this->sub_module;
        }
        return $this->attributes['sub_module'];
    }

    public function setModuleAttribute($val) {
        $this->module = strtolower($val);
        $this->attributes['module'] = strtolower($val);
    }

    public function setSubModuleAttribute($val) {
        $this->sub_module = strtolower($val);
        $this->attributes['sub_module'] = strtolower($val);
    }

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

    /*
     public function save(array $options = array()) {
     //if ($this->validate()) {
     return parent::save($options);
     //}


     return false;
     }
     */


    public function setSlugAttribute($value) {
        $this->attributes['slug'] = $this->makeSlugUnique($this->generateSlug($value));
    }


    function resize($file) {

        if (!File::exists($file)) {
            return false;
        }

        //_
        Image::make($file)->fit(100, 100, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file) . '/_' . basename($file));

        $sizes = config('provision_administration.image_sizes');

        if (empty($sizes) || count($sizes) < 1) {
            return true;
        }

        foreach ($sizes as $key => $size) {

            //check mode
            if (empty($size['mode']) || !in_array($size['mode'], [
                    'fit',
                    'resize'
                ])
            ) {
                \Debugbar::error('Image resize wrong mode! (key: ' . $key . ')');
                \Log::error('Image resize wrong mode! (key: ' . $key . ')');
                continue;
            }

            //make resize
            Image::make($file)->$size['mode']($size['width'], $size['height'], function ($c) use ($size) {
                if (!empty($size['aspectRatio']) && $size['aspectRatio'] === true) {
                    $c->aspectRatio();
                }
                if (!empty($size['upsize']) && $size['upsize'] === true) {
                    $c->upsize();
                }
            })->save(dirname($file) . '/' . $key . '_' . basename($file));

        }

        //B_


//        Image::make($file)->fit(400, 300, function ($c) {
//            $c->aspectRatio();
//            $c->upsize();
//        })->save(dirname($file) . '/C_' . basename($file));

        return true;
    }

    /*
     * media relations
     */
    public function media() {
        $relation = $this->hasMany(\ProVision\Administration\Media::class, 'item_id', 'id')
            ->where('module', $this->module)
            ->where('visible', 1)
            ->orderBy('order_index', 'asc');

        if ($this->sub_module != null) {
            $relation->where('sub_module', $this->sub_module);
        }

        return $relation;
    }

}

?>