<?php

namespace App\Services\Weather;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl;
    protected $cacheTtl;
    
    public function __construct()
    {
        $this->apiKey = config('services.weather.key');
        $this->baseUrl = config('services.weather.base_url');
        $this->cacheTtl = config('services.weather.cache_ttl');
    }
    
    public function getCurrentWeather(string $location)
    {
        $cacheKey = "weather:current:{$location}";
        
        return Cache::remember($cacheKey, $this->cacheTtl * 60, function() use ($location) {
            $response = Http::get("{$this->baseUrl}/current.json", [
                'key' => $this->apiKey,
                'q' => $location,
            ]);
            
            if ($response->failed()) {
                throw new \Exception("Weather API error: " . $response->body());
            }
            
            return $response->json();
        });
    }
    
    public function getForecast(string $location, int $days = 3)
    {
        $cacheKey = "weather:forecast:{$location}:{$days}";
        
        return Cache::remember($cacheKey, $this->cacheTtl * 60, function() use ($location, $days) {
            $response = Http::get("{$this->baseUrl}/forecast.json", [
                'key' => $this->apiKey,
                'q' => $location,
                'days' => $days,
            ]);
            
            if ($response->failed()) {
                throw new \Exception("Weather API error: " . $response->body());
            }
            
            return $response->json();
        });
    }
}