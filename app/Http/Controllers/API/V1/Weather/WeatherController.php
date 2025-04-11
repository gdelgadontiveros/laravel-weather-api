<?php

namespace App\Http\Controllers\API\V1\Weather;

use App\Http\Controllers\Controller;
use App\Http\Requests\Weather\GetWeatherRequest;
use App\Models\User;
use App\Models\WeatherRequest;
use App\Services\Weather\WeatherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Clima",
 *     description="Operaciones relacionadas con datos climáticos"
 * )
 */
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
     * @OA\Get(
     *     path="/api/v1/weather/current",
     *     tags={"Clima"},
     *     summary="Obtener clima actual",
     *     operationId="getCurrentWeather",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="location",
     *         in="path",
     *         description="Ciudad",
     *         @OA\Schema(type="string", example="Boston")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos climáticos",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="Boston",
     *                 value={
     *                  "location": {
     *                      "name": "Boston",
     *                      "region": "Massachusetts",
     *                      "country": "United States of America",
     *                      "lat": 42.3583,
     *                      "lon": -71.0603,
     *                      "tz_id": "America/New_York",
     *                      "localtime_epoch": 1744376204,
     *                      "localtime": "2025-04-11 08:56"
     *                  },
     *                  "current": {
     *                      "last_updated_epoch": 1744375500,
     *                      "last_updated": "2025-04-11 08:45",
     *                      "temp_c": 3.3,
     *                      "temp_f": 37.9,
     *                      "is_day": 1,
     *                      "condition": {
     *                          "text": "Overcast",
     *                          "icon": "//cdn.weatherapi.com/weather/64x64/day/122.png",
     *                          "code": 1009
     *                      },
     *                      "wind_mph": 3.4,
     *                      "wind_kph": 5.4,
     *                      "wind_degree": 91,
     *                      "wind_dir": "E",
     *                      "pressure_mb": 1025,
     *                      "pressure_in": 30.28,
     *                      "precip_mm": 2.48,
     *                      "precip_in": 0.1,
     *                      "humidity": 89,
     *                      "cloud": 100,
     *                      "feelslike_c": 2,
     *                      "feelslike_f": 35.6,
     *                      "windchill_c": -1.7,
     *                      "windchill_f": 29,
     *                      "heatindex_c": 1.4,
     *                      "heatindex_f": 34.5,
     *                      "dewpoint_c": 0.7,
     *                      "dewpoint_f": 33.2,
     *                      "vis_km": 16,
     *                      "vis_miles": 9,
     *                      "uv": 0.2,
     *                      "gust_mph": 4.4,
     *                      "gust_kph": 7.1
     *                  }
     *                 },
     *                 summary="Respuesta de ejemplo"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="Unauthenticated.",
     *                 value={
     *                  "message": "Unauthenticated."
     *                 },
     *                 summary="Respuesta de ejemplo"
     *             )
     *         )
     *     ),
     * )
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
     * @OA\Get(
     *     path="/api/v1/weather/forecast",
     *     tags={"Clima"},
     *     summary="Obtener pronóstico del clima",
     *     operationId="getWeatherForecast",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="location",
     *         in="path",
     *         description="Ciudad",
     *         @OA\Schema(type="string", example="Boston")
     *     ),
     *     @OA\Parameter(
     *         name="days",
     *         in="query",
     *         description="Número de días para el pronóstico",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Datos del pronóstico del clima",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="Boston",
     *                 value={
     *                 "location": {
     *                      "name": "Boston",
     *                      "region": "Massachusetts",
     *                      "country": "United States of America",
     *                      "lat": 42.3583,
     *                      "lon": -71.0603,
     *                      "tz_id": "America/New_York",
     *                      "localtime_epoch": 1744376204,
     *                      "localtime": "2025-04-11 08:56"
     *                 },
     *                 "forecast": {
     *                     "forecastday": {
     *                          "date": "2025-04-11",
     *                          "date_epoch": 1744329600,
     *                          "day": {
     *                              "maxtemp_c": 5.3,
     *                              "maxtemp_f": 41.5,
     *                              "mintemp_c": 1.1,
     *                              "mintemp_f": 34,
     *                              "avgtemp_c": 3.4,
     *                              "avgtemp_f": 38.1,
     *                              "maxwind_mph": 9.4,
     *                              "maxwind_kph": 15.1,
     *                              "totalprecip_mm": 2.48,
     *                              "totalprecip_in": 0.1,
     *                              "totalsnow_cm": 0,
     *                              "avgvis_km": 8.8,
     *                              "avgvis_miles": 5,
     *                              "avghumidity": 88,
     *                              "daily_will_it_rain": 0,
     *                              "daily_chance_of_rain": 62,
     *                              "daily_will_it_snow": 0,
     *                              "daily_chance_of_snow": 0,
     *                              "condition": {
     *                                  "text": "Patchy rain nearby",
     *                                  "icon": "//cdn.weatherapi.com/weather/64x64/day/176.png",
     *                                  "code": 1063
     *                              },
     *                              "uv": 1.1
     *                          },
     *                          "astro": {
     *                              "sunrise": "06:09 AM",
     *                              "sunset": "07:22 PM",
     *                              "moonrise": "06:19 PM",
     *                              "moonset": "05:30 AM",
     *                              "moon_phase": "Waxing Gibbous",
     *                              "moon_illumination": 96,
     *                              "is_moon_up": 1,
     *                              "is_sun_up": 0
     *                          },
     *                          "hour": {
     *                                {
     *                                  "time_epoch": 1744344000,
     *                                  "time": "2025-04-11 00:00",
     *                                  "temp_c": 4.7,
     *                                  "temp_f": 40.5,
     *                                  "is_day": 0,
     *                                  "condition": {
     *                                      "text": "Overcast ",
     *                                      "icon": "//cdn.weatherapi.com/weather/64x64/night/122.png",
     *                                      "code": 1009
     *                                  },
     *                                  "wind_mph": 7.8,
     *                                  "wind_kph": 12.6,
     *                                  "wind_degree": 203,
     *                                  "wind_dir": "SSW",
     *                                  "pressure_mb": 1027,
     *                                  "pressure_in": 30.33,
     *                                  "precip_mm": 0,
     *                                  "precip_in": 0,
     *                                  "snow_cm": 0,
     *                                  "humidity": 73,
     *                                  "cloud": 100,
     *                                  "feelslike_c": 2.6,
     *                                  "feelslike_f": 36.7,
     *                                  "windchill_c": 2.6,
     *                                  "windchill_f": 36.7,
     *                                  "heatindex_c": 4.7,
     *                                  "heatindex_f": 40.5,
     *                                  "dewpoint_c": 0.1,
     *                                  "dewpoint_f": 32.3,
     *                                  "will_it_rain": 0,
     *                                  "chance_of_rain": 0,
     *                                  "will_it_snow": 0,
     *                                  "chance_of_snow": 0,
     *                                  "vis_km": 10,
     *                                  "vis_miles": 6,
     *                                  "gust_mph": 10.9,
     *                                  "gust_kph": 17.6,
     *                                  "uv": 0
     *                              },
     *                           }
     *                      }
     *                  }
     *                 },
     *                 summary="Respuesta de ejemplo"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Examples(
     *                 example="Unauthenticated.",
     *                 value={
     *                  "message": "Unauthenticated."
     *                 },
     *                 summary="Respuesta de ejemplo"
     *             )
     *         )
     *     ),
     *  )
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
