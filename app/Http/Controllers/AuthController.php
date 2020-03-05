<?php

namespace App\Http\Controllers;

use App\Employees;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function generateToken(Employees $employee) {
        $payload = [
            'iss' => "Kouvee Petshop",
            'sub' => $employee->id,
            'iat' => time()
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    public function authenticate() {
        $employee = Employees::where('username', $this->request->username)->first();

        if(!$employee) {
            return response()->json(['message' => "Username or password is wrong", "data" => []], 400);
        }

        if(Hash::check($this->request->password, $employee->password)) {
            return response()->json(['message' => "Success", "data" => $employee, "token" => $this->generateToken($employee)], 200);
        } else {
            return response()->json(['message' => "Username or password is wrong", "data" => []], 400);
        }
    }
}
?>