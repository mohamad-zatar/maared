<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use App\Models\Setting;

class SettingsHelper
{
    public static function get($key, $default = null)
    {
        return Cache::rememberForever("setting_{$key}", function () use ($key, $default) {
            $setting = Setting::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    public static function set($key, $value)
    {
        Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forever("setting_{$key}", $value);
    }

    public static function clear($key)
    {
        Cache::forget("setting_{$key}");
    }
}
