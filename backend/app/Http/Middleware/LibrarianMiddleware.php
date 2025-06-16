<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LibrarianMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        dd(!$request->user() || !$request->user()->isLibrarian());
        if (!$request->user() || !$request->user()->isLibrarian())
        {
            return \response()->json(['message' => !$request->user()->isLibrarian()], 403);
        }

        return $next($request);
    }
}
