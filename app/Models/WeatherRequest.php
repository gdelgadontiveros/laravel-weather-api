<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="WeatherRequest",
 *     required={"user_id", "location"},
 *     @OA\Property(
 *         property="user_id",
 *         type="integer",
 *         @OA\Property(property="user_id", type="integer", example="1"),
 *     ),
 *     @OA\Property(
 *         property="location",
 *         type="string",
 *         @OA\Property(property="name", type="string", example="Boston"),
 *     ),
 *     @OA\Property(
 *         property="response_data",
 *         type="object",
 *         @OA\Property(property="current", type="object"),
 *         @OA\Property(property="location", type="object"),
 *         @OA\Property(property="forecast", type="object"),
 *     ),
 *    @OA\Property(
 *        property="ip_address",
 *        type="string",
 *        @OA\Property(property="ip_address", type="string", example="192.168.1.4"),
 *    ),
 *   @OA\Property(
 *      property="user_agent",
 *      type="string",
 *      @OA\Property(property="user_agent", type="string", example="Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3"),
 *    ),
 * )
 */
class WeatherRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'location',
        'response_data',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'response_data' => 'array',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * Get the user that made the request.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to filter by location.
     */
    public function scopeForLocation($query, $location)
    {
        return $query->where('location', 'like', "%{$location}%");
    }

    /**
     * Get the main weather condition from response data.
     */
    public function getMainConditionAttribute(): ?string
    {
        return $this->response_data['current']['condition']['text'] ?? null;
    }

    /**
     * Get the temperature from response data.
     */
    public function getTemperatureAttribute(): ?float
    {
        return $this->response_data['current']['temp_c'] ?? null;
    }
}
