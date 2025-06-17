<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function login(string $email, string $password): array
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные данные.'],
            ]);
        }

        $token = $user->createToken('token')->plainTextToken;

        return [
            'token' => $token,
            'userId' => $user->id,
            'userRole' => $user->role->name
        ];
    }

    public function logout(int $userId): void
    {
        User::find($userId)?->tokens()->delete();
    }
}
