<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use App\Employee;

class OnlyKasirMiddleware {
    public function handle($request, Closure $next, $guard =null) {

        if($request->auth->role === "Kasir") {
            return $next($request);
        }
        
        return response()->json([
            "message" => "Only Kasir allowed to perform the action",
            "data" => []
        ], 400);
    }
}
?>