<?php

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

if (!function_exists("cacheRemember")) {
    function cacheRemember(string $tag, string $key, string $time, Closure $callback)
    {
        if (!empty($tag) && method_exists(Cache::getStore(), $tag)) {
            return Cache::tags([$tag])->remember($key, $time, $callback);
        }

        return Cache::remember($key, $time, $callback);
    }
}

if (!function_exists("getCityByPincode")) {
    function getCityByPincode(int $pincode)
    {
        return cacheRemember("", "pincode" . $pincode, now()->addDays(30), function () use ($pincode) {
            try {
                $response = Http::withOptions([
                    'curl' => [
                        CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
                    ],
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0',
                    ],
                    'timeout' => 5,
                ])->get("https://api.postalpincode.in/pincode/{$pincode}");

                if (!$response->successful()) {
                    return null;
                }

                $data = json_decode($response->getBody(), true);
                if (
                    empty($data) ||
                    $data[0]['Status'] !== 'Success' ||
                    empty($data[0]['PostOffice'])
                ) {
                    return null;
                }

                $postOffice = $data[0]['PostOffice'][0];

                return [
                    'city'    => $postOffice['District'],
                    'state'   => $postOffice['State'],
                    'country' => $postOffice['Country'],
                ];
            } catch (RequestException $e) {
                Log::error($e->getMessage());
                return null;
            }
        });
    }
}
