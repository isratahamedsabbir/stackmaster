<?php

use App\Helpers\Helper;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\AuthApiCheckMiddleware;
use App\Http\Middleware\AuthCheckMiddleware;
use App\Http\Middleware\CustomerMiddleware;
use App\Http\Middleware\DeveloperMiddleware;
use App\Http\Middleware\OtpVerifiedMiddleware;
use App\Http\Middleware\RetailerMiddleware;
use App\Http\Middleware\TrainerMiddleware;
use App\Http\Middleware\UserMiddleware;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
            Route::middleware(['web', 'admin'])->prefix('admin')->name('admin.')->group(base_path('routes/backend.php'));
            Route::middleware(['web', 'client'])->prefix('client')->name('client.')->group(base_path('routes/client.php'));
            Route::middleware(['web', 'retailer'])->prefix('retailer')->name('retailer.')->group(base_path('routes/retailer.php'));
            Route::middleware(['web', 'developer'])->prefix('developer')->name('developer.')->group(base_path('routes/developer.php'));
            Route::middleware(['api', 'otp', 'user'])->prefix('api/user')->name('api.user.')->group(base_path('routes/user.php'));
            Route::middleware(['api', 'otp', 'trainer'])->prefix('api/trainer')->name('api.trainer.')->group(base_path('routes/trainer.php'));
        }
    )
    ->withBroadcasting(
        __DIR__.'/../routes/channels.php',
        ['prefix' => 'api', 'middleware' => ['auth:api']],
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'developer' => DeveloperMiddleware::class,
            'admin' => AdminMiddleware::class,
            'client' => CustomerMiddleware::class,
            'retailer' => RetailerMiddleware::class,
            'user' => UserMiddleware::class,
            'trainer' => TrainerMiddleware::class,
            'otp' => OtpVerifiedMiddleware::class,
            'authCheck' => AuthCheckMiddleware::class,
            'authApiAll' => AuthApiAllCheckMiddleware::class,
            'role' => RoleMiddleware::class,
            'permission' => PermissionMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class
        ]);
        /* $middleware->validateCsrfTokens(except: [
            'payment/stripe-webhook',
        ]); */
        /* $middleware->api([
            \Illuminate\Session\Middleware\StartSession::class,
        ]); */
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                if ($e instanceof ValidationException) {
                    return Helper::jsonErrorResponse($e->getMessage(), 422,$e->errors());
                }

                if ($e instanceof ModelNotFoundException) {
                    return Helper::jsonErrorResponse($e->getMessage(), 404);
                }

                if ($e instanceof AuthenticationException) {
                    return Helper::jsonErrorResponse( $e->getMessage(), 401);
                }
                if ($e instanceof AuthorizationException) {
                    return Helper::jsonErrorResponse( $e->getMessage(), 403);
                }
                // Dynamically determine the status code if available
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;

                return Helper::jsonErrorResponse($e->getMessage(), $statusCode);
            }else{
                return null;
            }
        });
    })->create();
