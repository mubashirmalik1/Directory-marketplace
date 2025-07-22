<?php

namespace App\Http\Controllers\API;

use App\Domains\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    public function __construct(public AuthService $authService)
    {
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = $this->authService->register($request->validated());
        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->success([
            'user' => UserResource::make($user),
            'token' => $token,
        ], 'User registered successfully', 201);
    }

    /**
     * Authenticate a user and return a token.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $user = $this->authService->login($request->validated());

        if (!$user) {
            return response()->error([], 'Invalid credentials', 401);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->success([
            'user' => UserResource::make($user),
            'token' => $token,
        ], 'Login successful');
    }

    /**
     * Send a password reset link to the user.
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->sendResetLink($request->validated());

        if ($status === Password::RESET_LINK_SENT) {
            return response()->success([], __($status));
        }

        return response()->error([], __($status), 400);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $status = $this->authService->resetPassword($request->validated());

        if ($status === Password::PASSWORD_RESET) {
            return response()->success([], __($status));
        }

        return response()->error([], 'Failed to reset password.', 400, ['email' => [__($status)]]);
    }

    /**
     * Get the authenticated user.
     */
    public function me(Request $request): JsonResponse
    {
        return response()->success(UserResource::make($request->user()));
    }

    /**
     * Log out the user (invalidate the token).
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->success([], 'Logged out successfully');
    }
} 