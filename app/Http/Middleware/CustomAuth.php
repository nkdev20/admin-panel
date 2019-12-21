<?php

namespace App\Http\Middleware;

use Closure;

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

        $token = $this->auth->setRequest($request)->getToken();
        
        $apiController  = app()->make('App\Http\Controllers\ApiController');
       
        try {
            $user = $this->auth->authenticate($token);
       
            if(!$user) {
                return $apiController->respondUnauthorizedError([
                    'message' => 'Invalid Token'
                ]);
            }
            // $tokenInstance = UserToken::where(['token' => $token, 'user_id' => $user->id]);
            // if (!$tokenInstance->count()) {
            //     return $apiController->respondUnauthorizedError([
            //         'message' => Config('messages.auth.invalid_request')
            //     ]);
            // }
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
