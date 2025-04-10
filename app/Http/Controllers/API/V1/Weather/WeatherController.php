<?php

namespace App\Http\Controllers\API\V1\Weather;

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
    
    /**
     * WeatherController constructor.
     *
     * @param WeatherService $weatherService
     */
    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }
    
    /**
     * Get the current weather for a specific location (Clima actual para una localizacion especifica).
     *
     * @param GetWeatherRequest $request
     * @return JsonResponse
     */
    public function current(GetWeatherRequest $request): JsonResponse
    {
        $location = $request->validated()['location'] ?? Auth::user()->location_preference;// localizacion o ubicacion favorita del usuario
        
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
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
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
    
    /**
     * Get the weather forecast for a specific location (Pronostico para una localizacion especifica).
     *
     * @param GetWeatherRequest $request
     * @return JsonResponse
     */
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
                'ip_address' => $request->ip(),
                'user_agent' => $request->header('User-Agent'),
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
