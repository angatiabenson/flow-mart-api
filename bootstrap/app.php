<?php

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Request;
use Symfony\Component\HttpFoundation\Response;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'api.key.auth' => \App\Http\Middleware\ApiKeyAuth::class,
            'token.expiration' => \App\Http\Middleware\CheckTokenExpiration::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            return response()->json([
                'message' => 'Not authenticated'
            ], 401);
        });

        $exceptions->respond(function (Response $response, Throwable $exception) {

            $statusCode = $response->getStatusCode();

            $response = [
                'code' => $statusCode,
                'status' => 'error',
                'message' => $exception->getMessage(),
            ];

            return response()->json($response, $statusCode);
        });
    })->create();