<?php

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
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
        $headers = [
            'Access-Control-Allow-Origin'      => '*',
            'Access-Control-Allow-Methods'     => 'POST, GET, OPTIONS, PUT, DELETE, PATCH',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400',
            'Access-Control-Allow-Headers'     => 'Content-Type, Authorization, X-Requested-With',
        ];


        // dd($headers);
        if ($request->isMethod('OPTIONS')) {
                return response()->json([], 200, $headers);
            }

        $response = $next($request);

        $response->header('Access-Control-Allow-Origin', '*');
        $response->header('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, PUT, DELETE, PATCH');
        $response->header('Access-Control-Allow-Credentials', 'true');
        $response->header('Access-Control-Max-Age', '86400');
        $response->header('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
        $response->header('Cache-Control', 'no-cache, must-revalidate');
        $response->header('X-XSS-Protection', '1; mode=block');
        $response->header('X-Frame-Options', 'SAMEORIGIN');
        $response->header('X-Content-Type-Options', 'nosniff');
        $response->header('Cache-Control', 'private, no-cache, no-store, must-revalidate');
        $response->header('Pragma', 'no-cache'); //HTTP 1.0
        $response->header('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');
        $response->header('X-Permitted-Cross-Domain-Policies', 'none');
        // $response->header('Expect-CT', "enforce, max-age=604800, report-uri='https://swordandscale.report-uri.com/r/d/ct/enforce'");
        $response->header('Referrer-Policy', 'no-referrer');
        $response->header('X-DNS-Prefetch-Control', 'off');
        $response->header('X-Download-Options', 'noopen');
        $response->header('Vary', 'Accept-Encoding');
        $response->header('Feature-Policy', "accelerometer 'none'; camera 'none'; geolocation 'none'; gyroscope 'none'; magnetometer 'none'; microphone 'none'; payment 'none'; usb 'none'");
        $response->header('Cache-Control', 'no-cache, must-revalidate');


        return $response;
    }
}
