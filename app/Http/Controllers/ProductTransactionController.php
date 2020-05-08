<?php

namespace App\Http\Controllers;

use App\ProductTransaction;
use App\ProductTransactionDetail;
use App\Product;
use App\Employee;
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
	*     path="/api/v1/producttransaction/getall",
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
}
?>