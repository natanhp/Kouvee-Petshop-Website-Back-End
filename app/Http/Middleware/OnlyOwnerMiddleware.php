<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Employee;

class OnlyOwnerMiddleware {
    public function handle($request, Closure $next, $guard =null) {

        if($request->auth->role === "Owner") {
            return $next($request);
        }
        
        return response()->json([
            "message" => "Only owner allowed to perform the action",
            "data" => []
        ], 400);
    }
}
?>