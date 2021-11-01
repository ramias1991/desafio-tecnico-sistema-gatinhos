<?php
namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{

    static public function getCache(string $nameCache) : array
    {
        return Cache::get($nameCache);
    }

    static public function validateCache(string $nameCache) : bool
    {
        return Cache::has($nameCache);
    }

    static public function saveCache(string $nameCache, array $contentCache) : void
    {
        Cache::put($nameCache, $contentCache);
    }

    static public function deleteCache(string $nameCache) : void
    {
        Cache::forget($nameCache);
    }

}
