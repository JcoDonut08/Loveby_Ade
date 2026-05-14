<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user !== null && Schema::hasColumn('users', 'last_active_at')) {
            $user->timestamps = false;
            $user->forceFill([
                'last_active_at' => now(),
            ])->saveQuietly();
            $user->timestamps = true;
        }

        return $next($request);
    }
}
