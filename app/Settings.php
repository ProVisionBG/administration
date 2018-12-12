<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use Illuminate\Http\UploadedFile;
use ProVision\Administration\Facades\Administration as AdministrationFacade;
use ProVision\Administration\Models\Settings as SettingsModel;
use ProVision\MediaManager\Models\MediaManager;
use ProVision\MediaManager\Traits\MediaManagerTrait;

class Settings
{

    use MediaManagerTrait;

    /**
     * Settings container
     *
     * @var array
     */
    protected $settings;

    /**
     * Запазване на насторйка
     *
     * @param string $key
     * @param mixed $value
     *
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

                    $filePath = '/public/uploads/settings/' . $setting->key . '/';
                    $m = new MediaManager();
                    $storage = $m->getStorageDisk();

                    /*
                     * remove old file
                     */
                    if (!empty($setting->value) && $files = $storage->allFiles($filePath)) {
                        //remove base file
                        $storage->delete($files);
                    }

                    $path = $storage->putFileAs($filePath, $value, $value->getClientOriginalName());
                    $this->resizeFile($storage->path($path));

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
     *
     * @param       $key
     * @param mixed $default
     *
     * @param mixed $locale
     *
     * @return mixed
     */
    public function getLocale($key, $default = false, $locale = false)
    {
        return $this->get(($locale ? $locale : AdministrationFacade::getLanguage()) . '.' . $key, $default);
    }

    /**
     * Вземане на настройка
     *
     * @param      $key
     * @param bool $default
     *
     * @return mixed
     */
    public function get($key, $default = false)
    {
        try {
            $value = $this->load()->get($key)->value;
            if (empty($value)) {
                return $default;
            }
            return $value;
        } catch (\Exception $exception) {
            return $default;
        }
    }

    /**
     * Зареждане на данните
     *
     * @param bool $force
     *
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
     * @param      $key
     * @param bool $size
     * @param bool $default
     *
     * @return bool
     */
    public function getFile($key, $size = false, $default = false)
    {
        try {
            $setting = $this->load()->get($key);

            $filePath = '/public/uploads/settings/' . $setting->key . '/';
            $m = new MediaManager();
            $storage = $m->getStorageDisk();

            if ($size) {
                return $storage->url($filePath . $size . '_' . $setting->value);
            } else {
                return $storage->url($filePath . $setting->value);
            }

        } catch (\Exception $exception) {
            return $default;
        }
    }

    /**
     * Взема всички настройки
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return $this->load();
    }

    /**
     * @return SettingsModel
     */
    public function getFormModel(): SettingsModel
    {
        $eloquent = new SettingsModel();

        foreach ($this->load() as $item) {
            $eloquent->setAttribute($item->key, $item->value);
        }

        return $eloquent;
    }

}
