<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Models\UserActivity;

class SessionTimeoutRedirect
{
    public function handle($request, Closure $next): mixed
    {
        if (Route::current() && in_array('auth', Route::current()->gatherMiddleware())) {
            if (!Auth::check()) {
                // Simpan aktivitas logout akibat timeout
                $this->logUserActivity($request);

                return redirect('/login')->with('error', 'Your session has expired due to inactivity.');
            }
        }

        return $next($request);
    }

    protected function logUserActivity($request)
    {
        if ($user = Auth::user()) {
            UserActivity::create([
                'user_id' => $user->id,
                'activity' => 'Logout',
                'type' => 'Session Timeout',
                'description' => 'User logged out due to session timeout.',
                'activity_date' => now(),
            ]);
        }
    }
}
