<?php

namespace App\Http\Controllers\API;

use App\Domains\Services\UserService;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(public UserService $userService)
    {
    }

    public function update(UpdateUserRequest $request): JsonResponse
    {
        $user = $this->userService->updateProfile($request->user(), $request->validated());

        return response()->success(new UserResource($user), 'Profile updated successfully.');
    }
}
