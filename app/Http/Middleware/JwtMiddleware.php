<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Exception;
use \Firebase\JWT\JWT;
use App\Employees;

class JwtMiddleware {
    public function handle($request, Closure $next, $guard =null) {

        $token = $request->bearerToken();
        
        if(!$token) {
            return response()->json([
                "message" => "Token not provided.",
                "data" => []
            ], 400);
        }
    

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(Exception $e) {
            return response()->json([
                "message" => $e,
                "data" => []
            ]);
        }

        $employee = Employees::find($credentials->sub);

        $request->auth = $employee;

        return $next($request);
    }
}