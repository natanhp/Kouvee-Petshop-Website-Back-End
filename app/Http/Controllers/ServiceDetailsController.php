<?php

namespace App\Http\Controllers;

use App\ServiceDetail;
use App\Service;
use App\PetType;
use App\PetSize;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ServiceDetailsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/noa/servicedetails/getall",
	*	  tags={"service details"},
    *     description="Get all the service details",
    *     @OA\Response(response="default", description="Get all the service details")
    * ),
    */
    public function getAll() {
        $service_details = ServiceDetail::all();
        $service_detail_complete = [];
    
        if(!$service_details) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($service_details as $service_detail) {
            $service_name = Service::find($service_detail->Services_id)->serviceName;
            $pet_type = PetType::find($service_detail->PetTypes_id)->type;
            $pet_size = PetSize::find($service_detail->PetSizes_id)->size;
            
            array_push($service_detail_complete, [
                "service_detail" => $service_detail,
                "complete_name" => "$service_name $pet_type $pet_size"
            ]);
        }

        return response()->json([
            "message" => "success",
            "data" => $service_detail_complete
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/servicedetails/insert",
     *     tags={"service details"},
     *     summary="Insert a new service detail",
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
     *                     property="PetTypes_id",
     *                     description="The id of the pet type",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="PetSizes_id",
     *                     description="The id of the pet size",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="Services_id",
     *                     description="The id of the service",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="The price of the service",
     *                     type="int",
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
            'price' => 'required|numeric',
            'PetTypes_id' => 'required|numeric',
            'PetSizes_id' => 'required|numeric',
            'Services_id' => 'required|numeric',
            'createdBy' => 'required|numeric',
        ]);

        $service_detail = new ServiceDetail;
        $service_detail->price = $request->price;
        $service_detail->PetTypes_id = $request->PetTypes_id;
        $service_detail->PetSizes_id = $request->PetSizes_id;
        $service_detail->Services_id = $request->Services_id;
        $service_detail->createdBy = $request->createdBy;

        if($service_detail->save()) {
            return response()->json([
                "message" => "Service detail created",
                "data" => $service_detail
            ], 200);
        } else {
            return response()->json([
                "message" => "Service detail not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/noa/servicedetails/getbyid/{id}",
	*	  tags={"service details"},
    *     description="Get an service by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the service detail",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a service detail by id")
    * ),
    */
    public function getServiceDetailById($id) {
        $service_detail = ServiceDetail::find($id);

        if($service_detail) {
            return response()->json([
                "message" => "Success",
                "data" => $service_detail
            ], 200);
        } else {
            return response()->json([
                "message" => "Service detail not found",
                "data" => []
            ], 400);
        }
    }
	
	 /**
     * @OA\Put(
     *     path="/api/v1/servicedetails/update",
     *     tags={"service details"},
     *     summary="Update a service detail",
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
     *                                  @OA\Property(
     *                     property="PetTypes_id",
     *                     description="The id of the pet type",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="PetSizes_id",
     *                     description="The id of the pet size",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="Services_id",
     *                     description="The id of the service",
     *                     type="int",
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     description="The price of the service",
     *                     type="int",
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
            'price' => 'required|numeric',
            'PetTypes_id' => 'required|numeric',
            'PetSizes_id' => 'required|numeric',
            'Services_id' => 'required|numeric',
            'updatedBy' => 'required|numeric',
            'id' => 'required|numeric',
        ]);

		$service_detail = ServiceDetail::find($request->id);
		if($service_detail) {
            $service_detail->price = $request->price;
            $service_detail->PetTypes_id = $request->PetTypes_id;
            $service_detail->PetSizes_id = $request->PetSizes_id;
            $service_detail->Services_id = $request->Services_id;
            $service_detail->updatedBy = $request->updatedBy;

			if($service_detail->save()) {
				return response()->json([
					"message" => "Service detail updated",
					"data" => $service_detail
				], 200);
			}
		}
        return response()->json([
            "message" => "Service detail not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/servicedetails/delete/{id}",
     *     tags={"service details"},
     *     summary="Deletes a service detail",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service detail id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Service detail not deleted it's because either the deletion failed or service detail to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id) {
		$service_detail = ServiceDetail::find($id);
		
		if($service_detail->delete()) {
			$service_detail->save();
			return response()->json([
				"message" => "Service detail deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Service detail not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/servicedetails/restore/{id}",
	*	  tags={"service details"},
	*     description="Restore the deleted service detail",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a service detail",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted service detail")
    * ),
    */
	public function restore($id) {
		$service_detail = ServiceDetail::onlyTrashed()->where('id', $id);
		
		if($service_detail) {
			$service_detail->restore();
			$service_detail = ServiceDetail::find($id);
			$service_detail->save();

			return response()->json([
				"message" => "Service detail restored",
				"data" => $service_detail
			], 200);
		} else {
			return response()->json([
				"message" => "Service detail not restored",
				"data" => []
			], 400);
		}
    }
}
?>