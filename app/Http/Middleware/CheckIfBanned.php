<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckIfBanned
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) { //not logged
            return $next($request);
        }

        if ($user->ban) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Your account has been banned.',
                'errors' => null,
            ], 403);
        }

        return $next($request);
    }
}
