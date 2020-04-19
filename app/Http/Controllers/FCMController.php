<?php

namespace App\Http\Controllers;

use App\FCM;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class FCMController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/fcm/getall",
	*	  tags={"fcm"},
    *     description="Get all the fcm token",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all the fcm token")
    * ),
    */
    public function getAll() {
        $fcms = FCM::all();
    
        if(!$fcms) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        return response()->json([
            "message" => "success",
            "data" => $fcms
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/fcm/insert",
     *     tags={"fcm"},
     *     summary="Insert a new fcm token",
     *     @OA\Response(
     *         response=400,
     *         description="Error"
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     description="The FCM token",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="employee_id",
     *                     description="The employee id",
     *                     type="int",
     *                 ),
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'token' => 'required',
            'employee_id' => 'required|numeric',
        ]);

        $fcm = new FCM;
        $fcm->token = $request->token;
        $fcm->employee_id = $request->employee_id;

        if($fcm->save()) {
            return response()->json([
                "message" => "FCM created",
                "data" => $fcm
            ], 200);
        } else {
            return response()->json([
                "message" => "FCM not created",
                "data" => []
            ], 400);
        }
    }
	
	/**
     * @OA\Delete(
     *     path="/api/v1/fcm/delete/{token}",
     *     tags={"fcm"},
     *     summary="Delete a fcm token",
     *     @OA\Parameter(
     *         name="token",
     *         in="path",
     *         description="Fcm id to be deleted",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Fcm not deleted it's because either the deletion failed or fcm to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($token) {
        $fcm = FCM::where("token", $token)->first();
		if($fcm->delete()) {
			return response()->json([
				"message" => "FCM is deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "FCM is not deleted",
				"data" => []
			], 400);
		}
	}
}
?>