<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Auth;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register()
    {
        $this->reportable(function (\Exception $e) {
            if (app()->environment('production')) {
                // Integração com serviço de monitoramento
                \Log::channel('slack')->error('Erro no checkout', [
                    'message' => $e->getMessage(),
                    'user' => Auth::id() ?? 'guest',
                    'url' => request()->fullUrl()
                ]);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => $exception->getMessage(),
                'trace' => config('app.debug') ? $exception->getTrace() : null
            ], 500);
        }

        return parent::render($request, $exception);
    }
}
