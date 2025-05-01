<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class ApiAuthentication
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        if (!$request->accepts(['application/json'])) {
            return response(['message' => 'Tidak dapat mengakses'], 406);
        }

        if (!$request->isMethod('post')) return $next($request);

        $token = $request->bearerToken();
        // $user = \App\User::where('api_token', hash('sha256', $token))->first();
        $user = User::whereRaw("api_token = ? AND status_user = ?", [$token, "aktif"])->first();
        if ($user) {
            $request->user = $user;
            return $next($request);
        }
        return response()->json([
            'success'   => false,
            'message' => 'Unauthenticated'
        ], 403);
    }
}
