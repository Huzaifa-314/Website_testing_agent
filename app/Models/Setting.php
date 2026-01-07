<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Cache duration in seconds (24 hours).
     */
    const CACHE_DURATION = 86400;

    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $cacheKey = "setting.{$key}";

        return Cache::remember($cacheKey, self::CACHE_DURATION, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();

            if (!$setting) {
                return $default;
            }

            return self::castValue($setting->value, $setting->type);
        });
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return Setting
     */
    public static function set(string $key, $value, string $type = 'string'): Setting
    {
        $setting = self::firstOrNew(['key' => $key]);
        $setting->value = self::prepareValue($value, $type);
        $setting->type = $type;
        $setting->save();

        // Clear cache
        Cache::forget("setting.{$key}");

        return $setting;
    }

    /**
     * Set multiple settings at once.
     *
     * @param array $settings
     * @return void
     */
    public static function setMany(array $settings): void
    {
        foreach ($settings as $key => $value) {
            if (is_array($value) && isset($value['value']) && isset($value['type'])) {
                self::set($key, $value['value'], $value['type']);
            } else {
                $type = self::detectType($value);
                self::set($key, $value, $type);
            }
        }
    }

    /**
     * Cast value based on type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    protected static function castValue($value, string $type)
    {
        return match ($type) {
            'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }

    /**
     * Prepare value for storage based on type.
     *
     * @param mixed $value
     * @param string $type
     * @return mixed
     */
    protected static function prepareValue($value, string $type)
    {
        return match ($type) {
            'boolean' => $value ? '1' : '0',
            'json' => json_encode($value),
            default => (string) $value,
        };
    }

    /**
     * Detect the type of a value.
     *
     * @param mixed $value
     * @return string
     */
    protected static function detectType($value): string
    {
        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if (is_array($value)) {
            return 'json';
        }

        return 'string';
    }

    /**
     * Clear all settings cache.
     *
     * @return void
     */
    public static function clearCache(): void
    {
        $settings = self::pluck('key');
        foreach ($settings as $key) {
            Cache::forget("setting.{$key}");
        }
    }
}
