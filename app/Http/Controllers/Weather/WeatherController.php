<?php

namespace App\Http\Controllers\Weather;

use App\Http\Controllers\Controller;
use App\Http\Requests\Weather\GetWeatherRequest;
use App\Models\User;
use App\Models\WeatherRequest;
use App\Services\Weather\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    protected $weatherService;
    
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    
    public function current(GetWeatherRequest $request): JsonResponse
    {
        $location = $request->validated()['location'] ?? Auth::user()->location_preference;
        
        if (!$location) {
            return response()->json([
                'message' => 'No location provided or set in preferences'
            ], 400);
        }
        
        try {
            $weatherData = $this->weatherService->getCurrentWeather($location);
            
            // Registrar la consulta
            WeatherRequest::create([
                'user_id' => Auth::id(),
                'location' => $location,
                'response_data' => $weatherData,
            ]);
            
            return response()->json($weatherData);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching weather data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function forecast(GetWeatherRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $location = $validated['location'] ?? Auth::user()->location_preference;
        $days = $validated['days'] ?? 3;
        
        if (!$location) {
            return response()->json([
                'message' => 'No location provided or set in preferences'
            ], 400);
        }
        
        try {
            $forecastData = $this->weatherService->getForecast($location, $days);
            
            WeatherRequest::create([
                'user_id' => Auth::id(),
                'location' => $location,
                'response_data' => $forecastData,
            ]);
            
            return response()->json($forecastData);
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching forecast data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
