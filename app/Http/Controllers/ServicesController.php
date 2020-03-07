<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ServicesController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/services/getall",
	*	  tags={"services"},
    *     description="Get all services",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all services")
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
     *     path="/api/v1/services/insert",
     *     tags={"services"},
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
     *                     property="serviceName",
     *                     description="The name of the service",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner who creates the service",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'serviceName' => 'required',
            'createdBy' => 'required|numeric'
        ]);

        $service = new Service;
        $service->serviceName = $request->serviceName;
        $service->createdBy = $request->createdBy;

        if($service->save()) {
            return response()->json([
                "message" => "Service created",
                "data" => $service
            ], 200);
        } else {
            return response()->json([
                "message" => "Service not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/services/getbyid/{id}",
	*	  tags={"services"},
    *     description="Get an service by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of customer",
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
     * @OA\Post(
     *     path="/api/v1/services/update",
     *     tags={"services"},
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
     *                     description="The id of the customer",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of the customer",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of the customer",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="dateBirth",
     *                     description="The birth date of the customer",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phoneNumber",
     *                     description="The phone number of the customer",
     *                     type="string"
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
            'dateBirth' => 'date',
            'phoneNumber' => 'numeric',
        ]);

		$service = Service::find($request->id);
		if($service) {
			$service->name = $request->name;
			$service->address = $request->address;
			$service->dateBirth = $request->dateBirth;
			$service->phoneNumber = $request->phoneNumber;
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
     *     path="/api/v1/services/delete/{id}/{ownerId}",
     *     tags={"services"},
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
	*     path="/api/v1/services/restore/{id}",
	*	  tags={"services"},
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