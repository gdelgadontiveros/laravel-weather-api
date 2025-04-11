<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateLocationRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Usuario",
 *     description="Operaciones relacionadas con el usuario"
 * )
 */
class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/v1/user/profile",
     *     tags={"Usuario"},
     *     summary="Obtener perfil de usuario",
     *     operationId="getProfile",
     *     @OA\Response(
     *         response=200,
     *         description="Perfil de usuario obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="integer", example=1),
     *             @OA\Property(property="name", type="string", example="John Doe"),    
     *              @OA\Property(property="email", type="string", example="johndoe@example.com"), 
     *             @OA\Property(property="location_preference", type="string", example="Boston"),
     *            @OA\Property(property="created_at", type="string", format="date-time"),
     *           @OA\Property(property="updated_at", type="string", format="date-time"),
     *        )
     *   )
     * )
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(new UserProfileResource($user));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/profile",
     *     tags={"Usuario"},
     *     summary="Actualizar perfil de usuario",
     *     operationId="updateProfile",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *            @OA\Property(property="password", type="string", format="password", example="Password123!"),
     *           @OA\Property(property="location_preference", type="string", example="Boston"),
     *        )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Perfil de usuario actualizado exitosamente",
     *      @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Profile updated successfully"),
     *         @OA\Property(property="user", ref="#/components/schemas/User")
     *     )
     *  ),
     *  @OA\Response(
     *     response=422,
     *     description="Error de validación",
     *     @OA\JsonContent(
     *         @OA\Property(property="message", type="string", example="Validation error"),
     *         @OA\Property(property="errors", type="object"),
     *         @OA\Property(property="name", type="array", @OA\Items(type="string", example="The name field is required.")),
     *         @OA\Property(property="email", type="array", @OA\Items(type="string", example="The email field is required.")),
     *         @OA\Property(property="password", type="array", @OA\Items(type="string", example="The password field is required.")),
     *       )
     *   ),
     * )
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        $user = Auth::user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => new UserProfileResource($user)
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/location",
     *     tags={"Usuario"},
     *     summary="Actualizar ubicación preferida del usuario",
     *     operationId="updateLocation",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"location"},
     *             @OA\Property(property="location", type="string", example="Boston"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ubicación preferida actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Location preference updated successfully"),
     *             @OA\Property(property="location", type="string", example="Boston")
     *         )
     *     )
     * )
     */
    public function updateLocation(UpdateLocationRequest $request): JsonResponse
    {
        $user = Auth::user();

        $user->update([
            'location_preference' => $request->validated()['location']
        ]);

        return response()->json([
            'message' => 'Location preference updated successfully',
            'location' => $user->location_preference
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/weather-history",
     *     tags={"Usuario"},
     *     summary="Obtener historial de solicitudes de clima",
     *     operationId="getWeatherHistory",
     *     @OA\Response(
     *         response=200,
     *         description="Historial de solicitudes de clima obtenido exitosamente",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/WeatherRequest")),
     *             @OA\Property(property="meta", type="object", 
     *                 @OA\Property(property="current_page", type="integer"),
     *                 @OA\Property(property="total_pages", type="integer"),
     *                 @OA\Property(property="total_requests", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function weatherHistory(): JsonResponse
    {
        $requests = Auth::user()
            ->weatherRequests()
            ->latest()
            ->paginate(10);

        return response()->json([
            'data' => $requests->items(),
            'meta' => [
                'current_page' => $requests->currentPage(),
                'total_pages' => $requests->lastPage(),
                'total_requests' => $requests->total(),
            ]
        ]);
    }
}
