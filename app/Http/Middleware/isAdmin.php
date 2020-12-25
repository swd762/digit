<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class isAdmin
{
    // мидл для проверки роли при чтении данных пользователей


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        $role = $user->roles->first()->name;
        $user->role = $role;
        if ($user->role === 'admin') {
            return $next($request);
        } else {
            return response()->json(['error' => 'Unauthorized'], 403);
        }



    }
}
