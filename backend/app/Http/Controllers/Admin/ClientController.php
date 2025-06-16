<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreRequest;
use App\Http\Requests\Client\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\PasswordResetNotification;
use App\Notifications\PasswordSetNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['role'])->get();

        return UserResource::collection($users);
    }

    public function store(StoreRequest $request)
    {
        try {
            $data = $request->validated();

            $password = Str::random(10);
            $data['password'] = Hash::make($password);

            if (!isset($data['role_id'])) {
                $data['role_id'] = 3;
            }

            $user = User::create($data);
            $user->notify(new PasswordSetNotification($password));

            return response()->json(['message' => 'Пользователь успешно создан'], 201);
        } catch (\Exception $e) {
            Log::error('Ошибка создания пользователя: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function show(string $id)
    {
//        dd($user);
        $user = User::find($id);

        return new UserResource($user);
    }

    public function update(UpdateRequest $request, string $id)
    {
        $data = $request->validated();

        $user = User::find($id);

        $user->update($data);

        return response()->json(null, 204);
    }

    public function destroy(string $id)
    {
        $user = User::find($id);

        if(auth()->user()->id === $user->id)
        {
            return response()->json(['error' => 'ты не можешь удалить себя'], 403);
        }

        $user->delete();

        return response()->json(null, 204);
    }

    public function resetPassword(string $userId)
    {
        $newPassword = str::random(10);

        $user = User::find($userId);
        $user->notify(new PasswordResetNotification($newPassword));

        $user->update(['password' => Hash::make($newPassword)]);

        return response()->json(null, 204);
    }
}
