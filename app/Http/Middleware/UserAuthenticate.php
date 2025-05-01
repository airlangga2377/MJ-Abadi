<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserAuthenticated
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
        if ($request->session()->exists('isLogin')) { 
            try {
                $token = decrypt($request->session()->get('isLogin'));

                # Get data user
                $user = User::whereRaw('api_token = ? AND status_user = ?', [$token, 'aktif'])->first();

                if($user){
                    $request->user = $user;
                    return $next($request); 
                }
            } catch (\Throwable $th) {
                Log::error($th); 
            }
        }
        return redirect('login')->with(['message' => 'Silahkan masuk kembali']);
    }
}
