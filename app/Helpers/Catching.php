<?php

namespace App\Helpers;
use Illuminate\Support\Facades\Cache;

class Catching
{
    public static function set($key, $value)
    {
        if(Cache::has($key)) {
            Cache::forget($key);
        }
        Cache::put($key, $value, now()->addHours(24));
        return true;
    }

    public static function get($key)
    {
        return Cache::has($key) ? Cache::get($key) : null;
    }

    public static function delete($key)
    {
        return Cache::has($key) ? Cache::forget($key) : false;
    }

    public static function clear()
    {
        return Cache::flush();
    }
}
