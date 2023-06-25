<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('token')) {
            $apiToken = $request->input('token');
            if ($apiToken == 'tokendiprova') {
                return $next($request);
            }
        }
        return response()->json(
            [
                'success' => false,
                'message' => 'Token non corretto.',
            ]
        );
    }
}
