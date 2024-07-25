<?php

namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;

interface AuthService
{
    public function login(LoginRequest $request): array;

    public function register(RegistrationRequest $request): array;

    public function logout(): void;
}