<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();
        
        // Detaylı log
        Log::info('Admin check', [
            'user_id' => $user->id,
            'is_admin' => $user->is_admin,
            'raw_user' => $user->toArray()
        ]);

        if (!$user || $user->is_admin !== true) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
} 