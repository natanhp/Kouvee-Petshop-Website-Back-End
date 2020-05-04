<?php

namespace App\Http\Controllers;

use App\Employee;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use \Firebase\JWT\JWT;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    protected function generateToken(Employee $employee) {
        $payload = [
            'iss' => "Kouvee Petshop",
            'sub' => $employee->id,
            'iat' => time()
        ];

        return JWT::encode($payload, env('JWT_SECRET'));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     tags={"employees"},
     *     summary="Employee login",
     *     @OA\Response(
     *         response=400,
     *         description="Error"
     *     ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
	 * 				   @OA\Property(
     *                     property="username",
     *                     description="The username of the employee",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="The password of the employee",
     *                     type="password",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function authenticate() {
        $employee = Employee::where('username', $this->request->username)->first();

        if(!$employee) {
            return response()->json(['message' => "Username or password is wrong", "data" => []], 400);
        }

        if(Hash::check($this->request->password, $employee->password)) {
            $employee->token = $this->generateToken($employee);
            return response()->json(['message' => "Success", "data" => [$employee]], 200);
        } else {
            return response()->json(['message' => "Username or password is wrong", "data" => []], 400);
        }
    }
}
?>