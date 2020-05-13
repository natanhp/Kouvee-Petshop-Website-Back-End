<?php

namespace App\Http\Controllers;

use App\ProductTransaction;
use App\ProductTransactionDetail;
use App\Product;
use App\Employee;
use App\Events\ProductMinReached;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ProductTransactionController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/producttransaction/kasir/getall",
	*	  tags={"product transaction"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get all the product transaction",
    *     @OA\Response(response="default", description="Product transaction and its detail")
    * ),
    */
    public function getAll() {
        $product_transactions = ProductTransaction::where('updatedBy', NULL)->get();
    
        if(!$product_transactions) {
            return response()->json([
                "message" => "error",
                "data" => []
            ], 400);
        }

        foreach($product_transactions as $product_transaction) {
            $product_transaction->employee_name = Employee::find($product_transaction->createdBy)->name;
            $product_transaction_details = ProductTransactionDetail::where('ProductTransaction_id', $product_transaction->id)->get();
            foreach($product_transaction_details as $product_transaction_detail) {
                $product_transaction_detail->product_name = Product::find($product_transaction_detail->Products_id)->productName;
                $product_transaction_detail->measurement = Product::find($product_transaction_detail->Products_id)->meassurement;
                $product_transaction_detail->productPrice = Product::find($product_transaction_detail->Products_id)->productQuantity;
                $product_transaction_detail->productQuantity = Product::find($product_transaction_detail->Products_id)->productQuantity;
            }
            $product_transaction->productTransactionkDetails = $product_transaction_details;
        }

        return response()->json([
            "message" => "success",
            "data" => $product_transactions
        ], 200);
    }
    
    /**
     * @OA\Post(
     *     path="/api/v1/producttransaction/cs/insert",
     *     tags={"product transaction"},
     *     summary="Insert a new product transaction",
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
     *                     property="productTransactionDetails",
     *                     description="Array of Product Restock Detail",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the owner",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     description="The total price",
     *                     type="double",
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'createdBy' => 'required|numeric',
            'productTransactionDetails' => 'required',
            'total' => 'required|numeric'
        ]);

        $productTransactionDetails = $request->productTransactionDetails;
        foreach($productTransactionDetails as $item) {
            $product = Product::find($item['Products_id']);
            
            if($product->productQuantity - $item['itemQty'] < 0) {
                return response()->json([
                    "message" => "$product->productName habis",
                    "data" => []
                ], 400);
            }
        }

        $latest_id = ProductTransaction::latest()->withTrashed()->first();
        $current_id;
        $date = Carbon::parse(Carbon::now()->toDateString())->format('dmy');
        if ($latest_id == null) {
            $current_id = 'PR-'.$date.'-'.'00';    
        } else {
            $current_id = 'PR-'.$date.'-'.sprintf("%02d", substr($latest_id->id, strrpos($latest_id->id, '-') + 1) + 1);
        }

        $product_transaction = new ProductTransaction;
        $product_transaction->id = $current_id;
        $product_transaction->createdBy = $request->createdBy;
        $product_transaction->isPaid = 0;
        $product_transaction->total = $request->total;
        $product_transaction->Customers_id = $request->Customers_id;


        if($product_transaction->save()) {
            foreach($productTransactionDetails as $item) {
                $product_transaction_detail = new ProductTransactionDetail;
                $product_transaction_detail->itemQty = $item['itemQty'];
                $product_transaction_detail->Products_id = $item['Products_id'];
                $product_transaction_detail->createdBy = $item['createdBy'];
                $product_transaction_detail->ProductTransaction_id = $current_id;
                
                $product = Product::find($item['Products_id']);
                $product->productQuantity -= $item['itemQty'];
                $product->updatedBy = $item['createdBy'];
                $product->save();

                event(new ProductMinReached($product));

                $product_transaction_detail->save();
            }

         
            return response()->json([
                "message" => "Product transaction created",
                "data" => [$product_transaction]
            ], 200);
        }

        return response()->json([
            "message" => "Product transaction not created",
            "data" => []
        ], 400);
    }
}
?>