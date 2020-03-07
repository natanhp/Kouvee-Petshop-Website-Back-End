<?php

namespace App\Http\Controllers;

use App\Pet;
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
    *     @OA\Response(response="default", description="Get all the services")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => Service::all()
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
    *     description="Get an service by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the service",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a service by id")
    * ),
    */
    public function getServiceById($id) {
        $service = Service::find($id);

        if($service) {
            return response()->json([
                "message" => "Success",
                "data" => $service
            ], 200);
        } else {
            return response()->json([
                "message" => "Service not found",
                "data" => []
            ], 400);
        }
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/pets/getbyname/{serviceName}",
	*	  tags={"pets"},
    *     description="Get an service by service name",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="serviceName",
    *         in="path",
    *         description="Name of the service",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a service by service name")
    * ),
    */
    public function getServiceByName($serviceName) {
        $service = Service::where('serviceName', 'LIKE', "%$serviceName%")->get();

        if($service) {
            return response()->json([
                "message" => "Success",
                "data" => $service
            ], 200);
        } else {
            return response()->json([
                "message" => "Service not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Post(
     *     path="/api/v1/pets/update",
     *     tags={"pets"},
     *     summary="Update a service",
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
     *                     description="The id of the service",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="serviceName",
     *                     description="The name of the service",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the service",
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
            'serviceName' => 'required',
            'updatedBy' => 'required|numeric'
        ]);

		$service = Service::find($request->id);
		if($service) {
            $service->id = $request->id;
			$service->serviceName = $request->serviceName;
			$service->updatedBy = $request->updatedBy;

			if($service->save()) {
				return response()->json([
					"message" => "Service updated",
					"data" => $service
				], 200);
			}
		}
        return response()->json([
            "message" => "Service not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/pets/delete/{id}/{ownerId}",
     *     tags={"pets"},
     *     summary="Deletes a service",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who deletes the service",
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
	public function delete($id, $ownerId) {
		$service = Service::find($id);
		
		if($service->delete()) {
			$service->deletedBy = $ownerId;
			$service->save();
			return response()->json([
				"message" => "Service deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Service not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/pets/restore/{id}",
	*	  tags={"pets"},
	*     description="Restore the deleted service",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a service",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted service")
    * ),
    */
	public function restore($id) {
		$service = Service::onlyTrashed()->where('id', $id);
		
		if($service) {
			$service->restore();
			$service = Service::find($id);
			$service->deletedBy = NULL;
			$service->save();

			return response()->json([
				"message" => "Service restored",
				"data" => $service
			], 200);
		} else {
			return response()->json([
				"message" => "Service not restored",
				"data" => []
			], 400);
		}
	}
}
?>