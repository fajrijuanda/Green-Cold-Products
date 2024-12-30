<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomErrorHandling
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Tangani status HTTP tertentu
        if ($response->getStatusCode() === 403) {
            return redirect()->route('error.not-authorized'); // Arahkan ke halaman error 403
        } elseif ($response->getStatusCode() === 503) {
            return redirect()->route('error.under-maintenance'); // Arahkan ke halaman error 503
        } elseif ($response->getStatusCode() === 404) {
            return redirect()->route('error.index'); // Arahkan ke halaman error 404
        }

        return $response; // Jika tidak ada error, kembalikan response biasa
    }
}
