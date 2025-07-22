<?php

declare(strict_types=1);

namespace App\Domains\Services;

use App\Domains\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    public function __construct(public UserRepository $userRepository)
    {
    }

    public function register(array $data): User
    {
        return $this->userRepository->create($data);
    }

    public function login(array $credentials): ?User
    {
        if (! Auth::attempt($credentials)) {
            return null;
        }

        /** @var User */
        return Auth::user();
    }

    public function sendResetLink(array $data): string
    {
        return Password::sendResetLink($data);
    }

    public function resetPassword(array $data): string
    {
        return Password::reset($data, function (User $user, string $password) {
            $user->forceFill([
                'password' => Hash::make($password),
            ])->save();
        });
    }
} 