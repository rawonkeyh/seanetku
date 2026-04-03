<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use App\Models\Admin;

class AuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLogin()
    {
        // Redirect if already authenticated
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        
        return view('admin.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request)
    {
        // Validate input
        $credentials = $request->validate([
            'username' => 'required|string|max:255',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username tidak boleh kosong.',
            'password.required' => 'Password tidak boleh kosong.',
        ]);

        // Rate limiting key
        $throttleKey = $this->throttleKey($request);

        // Check if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            
            Log::warning('Admin login rate limit exceeded', [
                'ip' => $request->ip(),
                'username' => $request->username,
                'remaining_seconds' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'username' => sprintf(
                    'Terlalu banyak percobaan login. Silakan coba lagi dalam %d detik.',
                    $seconds
                ),
            ]);
        }

        // Attempt to authenticate
        if (Auth::guard('admin')->attempt($credentials, $request->filled('remember'))) {
            // Clear rate limiter on successful login
            RateLimiter::clear($throttleKey);
            
            // Regenerate session
            $request->session()->regenerate();
            
            // Log successful login
            Log::info('Admin login successful', [
                'admin_id' => Auth::guard('admin')->id(),
                'username' => $credentials['username'],
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return redirect()->intended(route('admin.dashboard'));
        }

        // Increment rate limiter on failed attempt
        RateLimiter::hit($throttleKey, 60); // Lock for 60 seconds after 5 attempts
        
        // Log failed attempt
        Log::warning('Admin login failed', [
            'username' => $credentials['username'],
            'ip' => $request->ip(),
            'remaining_attempts' => 5 - RateLimiter::attempts($throttleKey),
        ]);

        $remainingAttempts = 5 - RateLimiter::attempts($throttleKey);
        $errorMessage = 'Username atau password salah.';
        
        if ($remainingAttempts > 0 && $remainingAttempts <= 3) {
            $errorMessage .= sprintf(' Sisa percobaan: %d kali.', $remainingAttempts);
        }

        return back()
            ->withErrors(['username' => $errorMessage])
            ->onlyInput('username');
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        $adminId = Auth::guard('admin')->id();
        $username = Auth::guard('admin')->user()->username ?? 'Unknown';
        
        // Log logout
        Log::info('Admin logout', [
            'admin_id' => $adminId,
            'username' => $username,
            'ip' => $request->ip(),
        ]);
        
        // Logout
        Auth::guard('admin')->logout();
        
        // Invalidate session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('admin.login')
            ->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Get the rate limiting throttle key for the request
     */
    protected function throttleKey(Request $request): string
    {
        return 'admin_login_' . strtolower($request->input('username')) . '|' . $request->ip();
    }
}
