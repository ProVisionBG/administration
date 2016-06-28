<?php
namespace ProVision\Administration;

use Illuminate\Database\Eloquent\Model;
use Image;
use Validator;

class AdminModel extends Model {

    public $errors;

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
    }

    /*
     * error container
     */

    public static function boot() {
        parent::boot();

        static::saving(function ($model) {
            foreach ($model->attributes as $key => $value) {
                if ($value == '') {
                    $model->{$key} = null;
                } elseif (is_string($value)) {
                    //$model->{$key} = trim($value);
                }
            }
        });
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
        return $this->attributes['slug'] = $this->makeSlugUnique($this->generateSlug($value));
    }


    function resize($file) {

        //A_
        Image::make($file)->fit(100, 100, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file) . '/A_' . basename($file));

        //B_
        Image::make($file)->resize(400, null, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file) . '/B_' . basename($file));

        Image::make($file)->fit(400, 300, function ($c) {
            $c->aspectRatio();
            $c->upsize();
        })->save(dirname($file) . '/C_' . basename($file));

        return true;
    }

}

?>