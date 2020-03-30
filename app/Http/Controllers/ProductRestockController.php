<?php

namespace App\Http\Controllers;

use App\ProductRestock;
use App\Product;
use App\Employee;
use App\Supplier;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class ProductRestockController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/productrestock/getall",
	*	  tags={"product restock"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get all the product restock",
    *     @OA\Response(response="default", description="Get all the product restock")
    * ),
    */
    public function getAll() {
        $product_restocks = ProductRestock::all();
    
        if(!$product_restocks) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($product_restocks as $product_restock) {
            $product_restock->supplier_name = Supplier::find($product_restock->Suppliers_id)->name;
            $product_restock->product_name = Product::find($product_restock->Products_id)->productName;
            $product_restock->employee_name = Employee::find($product_restock->Employees_id)->name;
        }

        return response()->json([
            "message" => "success",
            "data" => $product_restocks
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/productrestock/insert",
     *     tags={"product restock"},
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

        // $service_detail = new ServiceDetail;
        // $service_detail->price = $request->price;
        // $service_detail->PetTypes_id = $request->PetTypes_id;
        // $service_detail->PetSizes_id = $request->PetSizes_id;
        // $service_detail->Services_id = $request->Services_id;
        // $service_detail->createdBy = $request->createdBy;

        // if($service_detail->save()) {
        //     return response()->json([
        //         "message" => "Service detail created",
        //         "data" => $service_detail
        //     ], 200);
        // } else {
        //     return response()->json([
        //         "message" => "Service detail not created",
        //         "data" => []
        //     ], 400);
        // }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/productrestock/getbyid/{id}",
	*	  tags={"product restock"},
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
        // $service_detail = ServiceDetail::find($id);

        // if($service_detail) {
        //     return response()->json([
        //         "message" => "Success",
        //         "data" => $service_detail
        //     ], 200);
        // } else {
        //     return response()->json([
        //         "message" => "Service detail not found",
        //         "data" => []
        //     ], 400);
        // }
    }
	
	 /**
     * @OA\Put(
     *     path="/api/v1/productrestock/update",
     *     tags={"product restock"},
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

		// $service_detail = ServiceDetail::find($request->id);
		// if($service_detail) {
        //     $service_detail->price = $request->price;
        //     $service_detail->PetTypes_id = $request->PetTypes_id;
        //     $service_detail->PetSizes_id = $request->PetSizes_id;
        //     $service_detail->Services_id = $request->Services_id;
        //     $service_detail->updatedBy = $request->updatedBy;

		// 	if($service_detail->save()) {
		// 		return response()->json([
		// 			"message" => "Service detail updated",
		// 			"data" => $service_detail
		// 		], 200);
		// 	}
		// }
        // return response()->json([
        //     "message" => "Service detail not updated",
        //     "data" => []
        // ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/productrestock/delete/{id}",
     *     tags={"product restock"},
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
		// $service_detail = ServiceDetail::find($id);
		
		// if($service_detail->delete()) {
		// 	$service_detail->save();
		// 	return response()->json([
		// 		"message" => "Service detail deleted",
		// 		"data" => []
		// 	], 200);
		// } else {
		// 	return response()->json([
		// 		"message" => "Service detail not deleted",
		// 		"data" => []
		// 	], 400);
		// }
	}


	/**
    * @OA\Get(
	*     path="/api/v1/productrestock/restore/{id}",
	*	  tags={"product restock"},
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
	// 	$service_detail = ServiceDetail::onlyTrashed()->where('id', $id);
		
	// 	if($service_detail) {
	// 		$service_detail->restore();
	// 		$service_detail = ServiceDetail::find($id);
	// 		$service_detail->save();

	// 		return response()->json([
	// 			"message" => "Service detail restored",
	// 			"data" => $service_detail
	// 		], 200);
	// 	} else {
	// 		return response()->json([
	// 			"message" => "Service detail not restored",
	// 			"data" => []
	// 		], 400);
	// 	}
    }
}
?>