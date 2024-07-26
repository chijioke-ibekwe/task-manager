<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegistrationRequest;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Authenticate a user
     *
     * @param string $taskId 
     * @return JsonResponse
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $response = $this->authService->login($request);
            return $this->response('User logged in successfully', $response);
        } catch (AuthenticationException $e) {
            return $this->response($e->getMessage(), code: 401);
        }

    }

    public function register(RegistrationRequest $request)
    {
        $response = $this->authService->register($request);
        return $this->response('User created successfully', $response, 201);
    }

    public function logout()
    {
        $this->authService->logout();
        return $this->response('User logged out successfully');
    }

}
