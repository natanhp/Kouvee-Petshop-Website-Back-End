<?php

namespace App\Http\Controllers;

use App\ProductRestock;
use App\ProductRestockDetail;
use App\Product;
use App\Employee;
use App\Supplier;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            $product_restock->employee_name = Employee::find($product_restock->createdBy)->name;
            $product_restock->productRestockDetails;
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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="Suppliers_id",
     *                     description="The id of the supplier",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="isArrived",
     *                     description="The arrival status",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="productRestockDetails",
     *                     description="Array of Product Restock Detail",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner",
     *                     type="integer",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'Suppliers_id' => 'required|numeric',
            'createdBy' => 'required|numeric',
            'isArrived' => 'required|numeric',
            'productRestockDetails' => 'required',
        ]);

        $latest_id = ProductRestock::latest()->withTrashed()->first();
        $current_id;
        if ($latest_id == null) {
            $current_id = 'PO-'.Carbon::now()->toDateString().'-'.'00';    
        } else {
            $current_id = 'PO-'.Carbon::now()->toDateString().'-'.sprintf("%02d", substr($latest_id->id, strrpos($latest_id->id, '-') + 1) + 1);
        }

        $product_restock = new ProductRestock;
        $product_restock->id = $current_id;
        $product_restock->createdBy = $request->createdBy;
        $product_restock->isArrived = $request->isArrived;
        $product_restock->Suppliers_id = $request->Suppliers_id;

        if($product_restock->save()) {
            foreach($request->productRestockDetails as $item) {
                $product_restock_detail = new ProductRestockDetail;
                $product_restock_detail->itemQty = $item['itemQty'];
                $product_restock_detail->Products_id = $item['Products_id'];
                $product_restock_detail->createdBy = $item['createdBy'];
                $product_restock_detail->product_restock_id = $current_id;
                $created_by = $item['createdBy'];

                $product_restock_detail->save();
            }

         
            return response()->json([
                "message" => "Product restock created",
                "data" => $product_restock
            ], 200);
        }
        return response()->json([
            "message" => "Product restock not created",
            "data" => []
        ], 400);
    }


    /**
    * @OA\Get(
	*     path="/api/v1/productrestock/getbyid/{id}",
	*	  tags={"product restock"},
    *     description="Get an product restock by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of the product restock",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a service detail by id")
    * ),
    */
    public function getProductRestockById($id) {
        $product_restock = ProductRestock::find($id)->first();
        $product_restock->supplier_name = Supplier::find($product_restock->Suppliers_id)->name;
        $product_restock->product_name = Product::find($product_restock->Products_id)->productName;
        $product_restock->employee_name = Employee::find($product_restock->createdBy)->name;

        if($product_restock) {
            return response()->json([
                "message" => "Success",
                "data" => $product_restock
            ], 200);
        } else {
            return response()->json([
                "message" => "Product restock not found",
                "data" => []
            ], 400);
        }
    }
	
	/**
     * @OA\Delete(
     *     path="/api/v1/productrestock/delete/{id}",
     *     tags={"product restock"},
     *     summary="Deletes a product restock",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product restock id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Product restock not deleted it's because either the deletion failed or product restock to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id) {
		$product_restock = ProductRestock::find($id);
		
		if($product_restock->delete()) {
			$product_restock->save();
			return response()->json([
				"message" => "Product restock deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Product restock not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/productrestock/restore/{id}",
	*	  tags={"product restock"},
	*     description="Restore the deleted product restock",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of a product restock",
    *         required=true,
    *         @OA\Schema(
    *             type="string"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted product restock")
    * ),
    */
	public function restore($id) {
		$product_restock = ProductRestock::onlyTrashed()->where('id', $id);
		
		if($product_restock) {
			$product_restock->restore();
			$product_restock = ProductRestock::find($id);
			$product_restock->save();

			return response()->json([
				"message" => "Product restock restored",
				"data" => $product_restock
			], 200);
		} else {
			return response()->json([
				"message" => "Product restock not restored",
				"data" => []
			], 400);
		}
    }
}
?>