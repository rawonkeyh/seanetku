<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Log;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuth::class,
        ]);
        
        // CSRF token validation exceptions
        $middleware->validateCsrfTokens(except: [
            'api/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Render custom response for 419 errors with detailed logging
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            try {
                $sessionToken = null;
                $sessionId = null;
                if ($request->hasSession()) {
                    try {
                        $sessionToken = $request->session()->token();
                        $sessionId = $request->session()->getId();
                    } catch (\Exception $sessionError) {
                        $sessionToken = 'ERROR';
                    }
                }
                
                Log::error('🚫 419 CSRF TOKEN MISMATCH ERROR', [
                    '❌ DETAILS' => [
                        'url' => $request->fullUrl(),
                        'method' => $request->method(),
                        'ip' => $request->ip(),
                    ],
                    '🌐 REQUEST' => [
                        'user_agent' => substr($request->userAgent() ?? 'N/A', 0, 100),
                        'referer' => $request->header('referer') ?? 'N/A',
                    ],
                    '🔐 SESSION INFO' => [
                        'has_session' => $request->hasSession(),
                        'session_id' => $sessionId ?? 'NO SESSION',
                        'session_token' => $sessionToken ? substr($sessionToken, 0, 30) . '...' : 'NULL',
                        'session_driver' => config('session.driver'),
                        'session_lifetime' => config('session.lifetime') . ' minutes',
                    ],
                    '🎫 TOKENS' => [
                        'input_token' => $request->input('_token') ? substr($request->input('_token'), 0, 30) . '...' : 'NULL',
                        'header_token' => $request->header('X-CSRF-TOKEN') ? substr($request->header('X-CSRF-TOKEN'), 0, 30) . '...' : 'NULL',
                    ],
                    '🍪 COOKIES' => [
                        'cookies_count' => count($request->cookies->all()),
                        'cookie_names' => implode(', ', array_keys($request->cookies->all())),
                    ],
                    '💡 POSSIBLE CAUSES' => [
                        '1. Browser cookies disabled or blocked',
                        '2. Session expired (lifetime: ' . config('session.lifetime') . ' min)',
                        '3. Session files not writable (check storage/framework/sessions)',
                        '4. Multiple browser tabs with different sessions',
                        '5. Browser cache issue - clear cookies and try again',
                    ],
                ]);
            } catch (\Exception $logError) {
                Log::error('Failed to log 419 error details: ' . $logError->getMessage());
            }
            
            // Return default 419 page (don't interfere with normal flow)
            return null;
        });
    })->create();
