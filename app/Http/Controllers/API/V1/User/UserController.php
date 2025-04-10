<?php

namespace App\Http\Controllers\API\V1\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UpdateLocationRequest;
use App\Http\Requests\User\UpdateProfileRequest;
use App\Http\Resources\UserProfileResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        $user = Auth::user();
        return response()->json(new UserProfileResource($user));
    }

    /**
     * Update the authenticated user's profile.
     *
     * @param UpdateProfileRequest $request
     * @return JsonResponse
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
     * Update the user's preferred location.
     *
     * @param UpdateLocationRequest $request
     * @return JsonResponse
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
     * Get the user's weather request history.
     *
     * @return JsonResponse
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
