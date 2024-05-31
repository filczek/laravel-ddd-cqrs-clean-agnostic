<?php

use DDD\Shared\Infrastructure\Exceptions\RecordNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (RecordNotFoundException $e, Request $request) {
            $content = [
                'error' => [
                    'code' => (new ReflectionClass($e))->getShortName(),
                    'message' => $e->getMessage(),
                ]
            ];

            return response($content, Response::HTTP_NOT_FOUND);
        });

        //
    })
    ->create();
