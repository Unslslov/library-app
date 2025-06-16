<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $clientRoleId = Role::where('name', 'client')->value('id');

        $data['role_id'] = $clientRoleId;

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json(['user' => $user], 201);
    }

    public function login(LoginRequest $request)
    {
        $request->validated();

        $user = User::where('email', request('email'))->first();

        if(!$user || !Hash::check(request('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Неверные учетные записи.'],
            ]);
        };

        $token = $user->createToken('token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'userId' => $user->id,
            'userRole' => $user->role->name
        ]);
    }
    public function logout(string $id) {
        $user = User::find($id);
        $user->tokens()->delete();
        return response(null, 204);
    }
}
