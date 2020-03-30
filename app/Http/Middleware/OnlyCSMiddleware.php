<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Employee;

class OnlyCSMiddleware {
    public function handle($request, Closure $next, $guard =null) {

        if($request->auth->role === "CS") {
            return $next($request);
        }
        
        return response()->json([
            "message" => "Only CS allowed to perform the action",
            "data" => []
        ], 400);
    }
}
?>