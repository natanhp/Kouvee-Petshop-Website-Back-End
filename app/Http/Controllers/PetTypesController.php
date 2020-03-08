<?php

namespace App\Http\Controllers;

use App\PetType;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PetTypesController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/pettypes/getall",
	*	  tags={"pet types"},
    *     description="Get all the pet types",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all the pet types")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => PetType::all()
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/pettypes/insert",
     *     tags={"pet types"},
     *     summary="Insert a new service",
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
     *                     property="type",
     *                     description="The type of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner who creates the pet type",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'type' => 'required',
            'createdBy' => 'required|numeric'
        ]);

        $pet_type = new PetType;
        $pet_type->type = $request->type;
        $pet_type->createdBy = $request->createdBy;

        if($pet_type->save()) {
            return response()->json([
                "message" => "Pet Type created",
                "data" => $pet_type
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet TYpe not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/pettypes/getbyid/{id}",
	*	  tags={"pet types"},
    *     description="Get a pet type by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the pet type",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a pet type by id")
    * ),
    */
    public function getPetTypeById($id) {
        $pet_type = PetType::find($id);

        if($pet_type) {
            return response()->json([
                "message" => "Success",
                "data" => $pet_type
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet type not found",
                "data" => []
            ], 400);
        }
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/pettypes/getbytype/{type}",
	*	  tags={"pet types"},
    *     description="Get a pet type by type",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="type",
    *         in="path",
    *         description="Type of the pet",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a pet type by type")
    * ),
    */
    public function getPetTypeByType($type) {
        $pet_type = PetType::where('type', 'LIKE', "%$type%")->get();

        if($pet_type) {
            return response()->json([
                "message" => "Success",
                "data" => $pet_type
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet type not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Put(
     *     path="/api/v1/pettypes/update",
     *     tags={"pet types"},
     *     summary="Update a pet type",
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
	 * 				   @OA\Property(
     *                     property="id",
     *                     description="The id of the pet type",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     description="The type of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the pet type",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request) {

        $this->validate($request, [
            'id' => 'required|numeric',
            'type' => 'required',
            'updatedBy' => 'required|numeric'
        ]);

		$pet_type = PetType::find($request->id);
		if($pet_type) {
            $pet_type->id = $request->id;
			$pet_type->type = $request->type;
			$pet_type->updatedBy = $request->updatedBy;

			if($pet_type->save()) {
				return response()->json([
					"message" => "Pet type updated",
					"data" => $pet_type
				], 200);
			}
		}
        return response()->json([
            "message" => "Pet type not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/pettypes/delete/{id}/{ownerId}",
     *     tags={"pet types"},
     *     summary="Deletes a pet type",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pet type id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who deletes the pet type",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Pet type not deleted it's because either the deletion failed or Pet type to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$pet_type = PetType::find($id);
		
		if($pet_type->delete()) {
			$pet_type->deletedBy = $ownerId;
			$pet_type->save();
			return response()->json([
				"message" => "Pet type deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Pet type not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/pettypes/restore/{id}",
	*	  tags={"pet types"},
	*     description="Restore the deleted pet type",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a pet type",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted pet type")
    * ),
    */
	public function restore($id) {
		$pet_type = PetType::onlyTrashed()->where('id', $id);
		
		if($pet_type) {
			$pet_type->restore();
			$pet_type = PetType::find($id);
			$pet_type->deletedBy = NULL;
			$pet_type->save();

			return response()->json([
				"message" => "Pet type restored",
				"data" => $pet_type
			], 200);
		} else {
			return response()->json([
				"message" => "Pet type not restored",
				"data" => []
			], 400);
		}
	}
}
?>