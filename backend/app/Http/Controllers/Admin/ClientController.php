<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordSetNotification;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index()
    {
        $users = User::with(['role'])->get();

        return UserResource::collection($users);
    }

    public function store(StoreRequest $request)
    {
        try {
            $this->userService->createUser($request->validated());
            return response()->json(['message' => 'Пользователь успешно создан'], 201);
        } catch (\Exception $e) {
            Log::error('Ошибка создания пользователя: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show(string $id)
    {
        $user = User::find($id);

        return new UserResource($user);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $this->userService->updateUser($id, $request->validated());
        return response()->noContent();
    }

    public function destroy(string $id)
    {
        if (auth()->id() == $id) {
            return response()->json(['error' => 'Вы не можете удалить себя'], 403);
        }

        $this->userService->deleteUser($id);
        return response()->noContent();
    }

    public function resetPassword(string $userId)
    {
        $this->userService->resetPassword($userId);
        return response()->noContent();
    }
}
