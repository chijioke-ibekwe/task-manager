<?php

namespace App\Services\Implementations;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthServiceImpl implements AuthService
{

    private User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(LoginRequest $request): array
    {
        $validated = $request->validated();

        $token = Auth::attempt($validated);

        if (!$token) {
            throw new AuthenticationException('Invalid username or password');
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function register(RegistrationRequest $request): array
    {
        $validated = $request->validated();
        $validated['password'] = Hash::make($request->password);

        $user = User::create($validated);
        $token = Auth::login($user);

        return [
            'user' => $user,
            'token_details' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ];
    }

    public function logout(): void
    {
        Auth::logout();
    }
}