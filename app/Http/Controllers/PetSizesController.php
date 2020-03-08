<?php

namespace App\Http\Controllers;

use App\PetSize;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PetSizesController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/petsizes/getall",
	*	  tags={"pet sizes"},
    *     description="Get all the pet sizes",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all the pet sizes")
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
     *     path="/api/v1/petsizes/insert",
     *     tags={"pet sizes"},
     *     summary="Insert a new pet size",
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
     *                     property="size",
     *                     description="The size of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner who creates the pet size",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'size' => 'required',
            'createdBy' => 'required|numeric'
        ]);

        $size = $request->size;

        if(strcasecmp($size, "small") !== 0 && strcasecmp($size, "medium") !== 0 && 
            strcasecmp($size, "large") !== 0 && strcasecmp($size, "extra large") !== 0) {
                return response()->json([
                    "message" => "Size should be small, medium, large, or extra large",
                    "data" => []
                ], 400);
            }

        $pet_size = new PetSize;
        $pet_size->size = $size;
        $pet_size->createdBy = $request->createdBy;

        if($pet_size->save()) {
            return response()->json([
                "message" => "Pet Size created",
                "data" => $pet_size
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet Size not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/petsizes/getbyid/{id}",
	*	  tags={"pet sizes"},
    *     description="Get a pet size by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the pet size",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a pet size by id")
    * ),
    */
    public function getPetSizeById($id) {
        $pet_size = PetSize::find($id);

        if($pet_size) {
            return response()->json([
                "message" => "Success",
                "data" => $pet_size
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet size not found",
                "data" => []
            ], 400);
        }
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/petsizes/getbysize/{size}",
	*	  tags={"pet sizes"},
    *     description="Get a pet size by size",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="size",
    *         in="path",
    *         description="Size of the pet",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a pet size by size")
    * ),
    */
    public function getPetSizeBySize($size) {
        $pet_size = PetSize::where('size', 'LIKE', "%$size%")->get();

        if($pet_size) {
            return response()->json([
                "message" => "Success",
                "data" => $pet_size
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet size not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Put(
     *     path="/api/v1/petsizes/update",
     *     tags={"pet sizes"},
     *     summary="Update a pet size",
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
     *                     description="The id of the pet size",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="size",
     *                     description="The size of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the pet size",
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
            'size' => 'required',
            'updatedBy' => 'required|numeric'
        ]);

        $size = $request->size;

        if(strcasecmp($size, "small") !== 0 && strcasecmp($size, "medium") !== 0 && 
            strcasecmp($size, "large") !== 0 && strcasecmp($size, "extra large") !== 0) {
                return response()->json([
                    "message" => "Size should be small, medium, large, or extra large",
                    "data" => []
                ], 400);
            }

		$pet_size = PetSize::find($request->id);
		if($pet_size) {
            $pet_size->id = $request->id;
			$pet_size->size = $size;;
			$pet_size->updatedBy = $request->updatedBy;

			if($pet_size->save()) {
				return response()->json([
					"message" => "Pet size updated",
					"data" => $pet_size
				], 200);
			}
		}
        return response()->json([
            "message" => "Pet size not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/petsizes/delete/{id}/{ownerId}",
     *     tags={"pet sizes"},
     *     summary="Deletes a pet size",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pet size id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who deletes the pet size",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Pet size not deleted it's because either the deletion failed or Pet size to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$pet_size = PetSize::find($id);
		
		if($pet_size->delete()) {
			$pet_size->deletedBy = $ownerId;
			$pet_size->save();
			return response()->json([
				"message" => "Pet size deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Pet size not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/petsizes/restore/{id}",
	*	  tags={"pet sizes"},
	*     description="Restore the deleted pet size",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a pet size",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted pet size")
    * ),
    */
	public function restore($id) {
		$pet_size = PetSize::onlyTrashed()->where('id', $id);
		
		if($pet_size) {
			$pet_size->restore();
			$pet_size = PetSize::find($id);
			$pet_size->deletedBy = NULL;
			$pet_size->save();

			return response()->json([
				"message" => "Pet size restored",
				"data" => $pet_size
			], 200);
		} else {
			return response()->json([
				"message" => "Pet size not restored",
				"data" => []
			], 400);
		}
	}
}
?>