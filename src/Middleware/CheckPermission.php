<?php

namespace Abianbiya\Laralag\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permission
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $permission)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            return redirect('login');
        }

        // Check if the user has the required permission
        $user = Auth::user();
        // dump($user, $permission, $user->hasPermission($permission));
        if (!$user->hasPermission($permission)) {
            // If the user does not have permission, you can abort with a 403 or redirect them
            abort(403, 'Unauthorized action. '. $permission);
        }

        return $next($request);
    }
}
