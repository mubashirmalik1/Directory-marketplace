<?php

declare(strict_types=1);

namespace App\Domains\Services;

use App\Domains\Repositories\UserRepository;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(public UserRepository $userRepository)
    {
    }

    public function updateProfile(User $user, array $data): User
    {
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $this->userRepository->update($user->id, $data);
    }
} 