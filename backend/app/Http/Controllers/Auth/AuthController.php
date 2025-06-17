<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $result = $this->authService->login($credentials['email'], $credentials['password']);

        return response()->json($result);
    }
    public function logout(string $id) {
        $this->authService->logout($id);
        return response()->noContent();
    }
}
