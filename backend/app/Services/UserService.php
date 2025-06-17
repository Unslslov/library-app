<?php

namespace App\Services;

use App\Enums\Role;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordSetNotification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{
    public function createUser(array $data): User
    {
        $password = Str::random(10);
        $data['password'] = Hash::make($password);
        $data['role_id'] = $data['role_id'] ?? Role::CLIENT;

        $user = User::create($data);
        $user->notify(new PasswordSetNotification($password));

        return $user;
    }

    public function updateUser(int $id, array $data): void
    {
        User::findOrFail($id)->update($data);
    }

    public function deleteUser(int $id): void
    {
        $user = User::findOrFail($id);
        $user->delete();
    }

    public function resetPassword(int $userId): void
    {
        $newPassword = Str::random(10);
        $user = User::findOrFail($userId);

        $user->update(['password' => Hash::make($newPassword)]);
        $user->notify(new PasswordResetNotification($newPassword));
    }
}
