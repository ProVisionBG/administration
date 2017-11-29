<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use ProVision\Administration\Models\Settings as SettingsModel;
use ProVision\MediaManager\Traits\MediaManagerTrait;

class Settings
{

    use MediaManagerTrait;

    /**
     * Settings container
     * @var array
     */
    protected $settings;

    /**
     * Запазване на насторйка
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function save($key, $value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $this->save($key . '.' . $k, $v);
            }
        } else {
            $setting = SettingsModel::firstOrNew([
                'key' => $key
            ]);
            $setting->key = $key;

            if (is_object($value)) {
                $class = get_class($value);

                /*
                 * is file?
                 */
                if ($class == UploadedFile::class) {

                    $filePath = public_path('uploads/settings/' . $setting->key . '/');

                    /*
                     * remove old file
                     */
                    if (!empty($setting->value) && File::exists($filePath . $setting->value)) {
                        //remove base file
                        File::deleteDirectory($filePath, true);
                    }


                    $value->move($filePath, $value->getClientOriginalName());
                    $this->resizeFile($filePath . $value->getClientOriginalName());

                    $value = $value->getClientOriginalName();
                }
            }

            $setting->value = $value;

            $setting->save();
        }

        return true;
    }

    /**
     * Взимане на ключ с превод
     * @param $key
     * @param bool $default
     * @return mixed
     */
    public function getLocale($key, $default = false)
    {
        return $this->get(\Administration::getLanguage() . '.' . $key, $default);
    }

    /**
     * Вземане на настройка
     * @param $key
     * @param bool $default
     * @return mixed
     */
    public function get($key, $default = false)
    {
        try {
            return $this->load()->get($key)->value;
        } catch (\Exception $exception) {
            return $default;
        }
    }

    /**
     * Зареждане на данните
     * @param bool $force
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function load($force = false)
    {
        if (empty($this->settings) || $force) {
            $this->settings = SettingsModel::all()->keyBy('key');
        }

        return $this->settings;
    }

    /**
     * @param $key
     * @param bool $size
     * @param bool $default
     * @return bool
     */
    public function getFile($key, $size = false, $default = false)
    {
        try {
            $setting = $this->load()->get($key);

            if ($size) {
                return '/uploads/settings/' . $setting->key . '/' . $size . '_' . $setting->value;
            } else {
                return '/uploads/settings/' . $setting->key . '/' . $setting->value;
            }

        } catch (\Exception $exception) {
            return $default;
        }
    }

    /**
     * Взема всички настройки
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->load();
    }

    /**
     * @return SettingsModel
     */
    public function getFormModel()
    {
        $eloquent = new SettingsModel();
        foreach ($this->load() as $item) {
            $eloquent->setAttribute($item->key, $item->value);
        }

        return $eloquent;
    }

}