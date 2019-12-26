<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class CustomAuth
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
      
        $token = $request->header('Authorization');

        $apiController  = app()->make('App\Http\Controllers\ApiController');
       
        try {

            $user =  JWTAuth::parseToken()->authenticate();
           
            if(!$user) {
                return $apiController->respondUnauthorizedError([
                    'message' => 'Invalid Token'
                ]);
            }
        } catch (TokenExpiredException $e) {
            return $apiController->respondUnauthorizedError([
                'message' =>'Auth Token expired'
            ]);
        } catch (TokenInvalidException $e) {
            return $apiController->respondUnauthorizedError([
                'message' => 'Invalid token'
            ]);
        } catch (JWTException $e) {
            return $apiController->respondUnauthorizedError([
                'message' => 'Auth Token needed'
            ]);
        } 

        return $next($request);
    }
}
