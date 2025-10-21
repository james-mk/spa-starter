<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     */
    public function report(Throwable $exception): void
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // 🧭 Force JSON responses for API routes
        if ($request->is('api/*') || $request->expectsJson()) {

            // 🔐 Unauthenticated
            if ($exception instanceof AuthenticationException) {
                return response()->json([
                    'message' => 'Unauthenticated.',
                ], 401);
            }

            // 🚫 Forbidden / Access denied
            if (
                $exception instanceof AuthorizationException ||
                $exception instanceof AccessDeniedHttpException
            ) {
                return response()->json([
                    'message' => 'Access denied.',
                ], 403);
            }

            // ❌ Model not found (404)
            if ($exception instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Resource not found.',
                ], 404);
            }

            // 🚧 Invalid route or missing endpoint (404)
            if ($exception instanceof NotFoundHttpException) {
                return response()->json([
                    'message' => 'Endpoint not found.',
                ], 404);
            }

            // ⚠️ Validation errors
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'Validation failed.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            // 🧱 Generic fallback
            return response()->json([
                'message' => $exception->getMessage() ?: 'Server Error',
            ], $this->getStatusCode($exception));
        }

        // Default (web requests)
        return parent::render($request, $exception);
    }

    /**
     * Get status code for generic exceptions.
     */
    protected function getStatusCode(Throwable $exception): int
    {
        if (method_exists($exception, 'getStatusCode')) {
            return $exception->getStatusCode();
        }

        return 500;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest(route('login'));
    }
}
