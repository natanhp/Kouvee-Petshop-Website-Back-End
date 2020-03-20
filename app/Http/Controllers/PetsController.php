<?php

namespace App\Http\Controllers;

use App\Pet;
use App\PetType;
use App\PetSize;
use App\Customer;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class PetsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/pets/getall",
	*	  tags={"pets"},
    *     description="Get all the pets",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all the pets")
    * ),
    */
    public function getAll() {
        $pets = Pet::all();
        $pet_complete = [];
    
        if(!$pets) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($pets as $pet) {
            $pet_type = PetType::find($pet->PetTypes_id)->type;
            $pet_size = PetSize::find($pet->PetSizes_id)->size;
            $customer = Customer::find($pet->Customers_id)->name;
            
            array_push($pet_complete, [
                "pet" => $pet,
                "type" => $pet_type,
                "size" => $pet_size,
                "customer" => $customer
            ]);
        }

        return response()->json([
            "message" => "success",
            "data" => $pet_complete
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/pets/insert",
     *     tags={"pets"},
     *     summary="Insert a new pet",
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
     *                     property="name",
     *                     description="The name of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="dateBirth",
     *                     description="The birth date of the pet",
     *                     type="date",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner who creates the service",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="customers_id",
     *                     description="The id of the pet owner",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="petsizes_id",
     *                     description="The id of the pet size",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="pettypes_id",
     *                     description="The id of the size of the pet",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'createdBy' => 'required|numeric',
            'dateBirth' => 'required|date',
            'customers_id' => 'required|numeric',
            'petsizes_id' => 'required|numeric',
            'pettypes_id' => 'required|numeric',
        ]);

        $pet = new Pet;
        $pet->name = $request->name;
        $pet->createdBy = $request->createdBy;
        $pet->dateBirth = $request->dateBirth;
        $pet->customers_id = $request->customers_id;
        $pet->petsizes_id = $request->petsizes_id;
        $pet->pettypes_id = $request->pettypes_id;

        if($pet->save()) {
            return response()->json([
                "message" => "Pet created",
                "data" => $pet
            ], 200);
        } else {
            return response()->json([
                "message" => "Pet not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/pets/getbyid/{id}",
	*	  tags={"pets"},
    *     description="Get a pet by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the pet",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a pet by id")
    * ),
    */
    public function getPetById($id) {
        $pets = Pet::find($id)->get();
        $pet_complete = [];
    
        if(!$pets) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($pets as $pet) {
            $pet_type = PetType::find($pet->PetTypes_id)->type;
            $pet_size = PetSize::find($pet->PetSizes_id)->size;
            $customer = Customer::find($pet->Customers_id)->name;
            
            array_push($pet_complete, [
                "pet" => $pet,
                "type" => $pet_type,
                "size" => $pet_size,
                "customer" => $customer
            ]);
        }

        return response()->json([
            "message" => "success",
            "data" => $pet_complete
        ], 200);
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/pets/getbyname/{name}",
	*	  tags={"pets"},
    *     description="Get a pet by pet name",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="name",
    *         in="path",
    *         description="Name of the pet",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a service by pet name")
    * ),
    */
    public function getPetByName($name) {
        $pets = Pet::where('name', 'LIKE', "%$name%")->get();
        $pet_complete = [];
    
        if(!$pets) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($pets as $pet) {
            $pet_type = PetType::find($pet->PetTypes_id)->type;
            $pet_size = PetSize::find($pet->PetSizes_id)->size;
            $customer = Customer::find($pet->Customers_id)->name;
            
            array_push($pet_complete, [
                "pet" => $pet,
                "type" => $pet_type,
                "size" => $pet_size,
                "customer" => $customer
            ]);
        }

        return response()->json([
            "message" => "success",
            "data" => $pet_complete
        ], 200);
	}
	
	 /**
     * @OA\Put(
     *     path="/api/v1/pets/update",
     *     tags={"pets"},
     *     summary="Update a pet",
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
     *                     property="id",
     *                     description="The id of the pet",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of the pet",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="dateBirth",
     *                     description="The birth date of the pet",
     *                     type="date",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The id of the owner who creates the service",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="customers_id",
     *                     description="The id of the pet owner",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="petsizes_id",
     *                     description="The id of the pet size",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="pettypes_id",
     *                     description="The id of the size of the pet",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request) {

        $this->validate($request, [
            'id' => 'required|numeric',
            'name' => 'required',
            'updatedBy' => 'required|numeric',
            'dateBirth' => 'required|date',
            'customers_id' => 'required|numeric',
            'petsizes_id' => 'required|numeric',
            'pettypes_id' => 'required|numeric',
        ]);

		$pet = Pet::find($request->id);
		if($pet) {
            $pet->name = $request->name;
            $pet->updatedBy = $request->updatedBy;
            $pet->dateBirth = $request->dateBirth;
            $pet->customers_id = $request->customers_id;
            $pet->petsizes_id = $request->petsizes_id;
            $pet->pettypes_id = $request->pettypes_id;


			if($pet->save()) {
				return response()->json([
					"message" => "Pet updated",
					"data" => $pet
				], 200);
			}
		}
        return response()->json([
            "message" => "Pet not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/pets/delete/{id}/{csId}",
     *     tags={"pets"},
     *     summary="Deletes a pet",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Pet id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="csId",
     *         in="path",
     *         description="CS who deletes the pet",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Service not deleted it's because either the deletion failed or service to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $csId) {
		$pet = Pet::find($id);
		
		if($pet->delete()) {
			$pet->deletedBy = $csId;
			$pet->save();
			return response()->json([
				"message" => "Pet deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Pet not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/pets/restore/{id}",
	*	  tags={"pets"},
	*     description="Restore the deleted pet",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a pet",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted pet")
    * ),
    */
	public function restore($id) {
		$pet = Pet::onlyTrashed()->where('id', $id)->first();
		
		if($pet) {
			$pet->restore();
			$pet = Pet::find($id);
			$pet->deletedBy = NULL;
			$pet->save();

			return response()->json([
				"message" => "Pet restored",
				"data" => $pet
			], 200);
		} else {
			return response()->json([
				"message" => "Pet not restored",
				"data" => []
			], 400);
		}
	}
}
?>