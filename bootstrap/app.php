<?php

use App\Enums\ResponseStatus;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => 'Resource route not found',
                'data' => null
            ], 404);
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            $errors = $e->validator->errors()->all();

            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => $errors[0],
                'data' => $errors
            ], 422);
        });


        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => 'Method not allowed',
                'data' => null
            ], 405);
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => 'You are not authenticated',
                'data' => null
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) {
            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => 'You do not have permission to perform this action',
                'data' => null
            ], 403);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) {
            return response()->json([
                'status' => ResponseStatus::FAILURE,
                'message' => 'Model not found',
                'data' => null
            ], 404);
        });

    })->create();
