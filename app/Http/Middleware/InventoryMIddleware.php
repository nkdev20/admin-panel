<?php

namespace App\Http\Middleware;

use Closure;

class InventoryMiddleware
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
        $apiController  = app()->make('App\Http\Controllers\ApiController');
        if (!auth()->check()) {
            return $apiController->respondUnauthorizedError([
                'message' => 'Invalid Token'
            ]);
        }

        $user = auth()->user();
        
        $roles = $user->role()->pluck('role')->toArray();
        if ($user->store_manager_approved  == 0 && !in_array('Store Manager', $roles)) {
            return $apiController->respondUnauthorizedError([
                'message' => 'Unauthorised access'
            ]);
        }

        return $next($request);
    }
}
