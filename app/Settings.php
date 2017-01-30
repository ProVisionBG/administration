<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration;

use ProVision\Administration\Models\Settings as SettingsModel;

class Settings
{

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

        foreach ($this->load() as $item) {
            if ($item->key == $key) {
                return $item->value;
            }
        }

        return $default;
    }

    /**
     * Зареждане на данните
     * @param bool $force
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    protected function load($force = false)
    {
        if (empty($this->settings) || $force) {
            $this->settings = SettingsModel::all();
        }

        return $this->settings;
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
