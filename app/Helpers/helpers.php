<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists("cacheRemember")) {
    function cacheRemember(string $tag, string $key, int $time, Closure $callback)
    {
        if (!empty($tag) && method_exists(Cache::getStore(), $tag)) {
            return Cache::tags([$tag])->remember($key, $time, $callback);
        }

        return Cache::remember($key, $time, $callback);
    }
}
