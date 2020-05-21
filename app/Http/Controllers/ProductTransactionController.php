<?php

namespace App\Http\Controllers;

use App\ProductTransaction;
use App\ProductTransactionDetail;
use App\Product;
use App\Employee;
use App\Customer;
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
        $product_transactions = ProductTransaction::where('isPaid', 0)->get();
    
        if(!$product_transactions) {
            return response()->json([
                "message" => "Get data error",
                "data" => []
            ], 400);
        }

        foreach($product_transactions as $product_transaction) {
            $product_transaction->cs_name = Employee::find($product_transaction->createdBy)->name;
            $customer = Customer::find($product_transaction->Customers_id, ['id', 'name', 'phoneNumber']);
            
            if($customer == null) {
                $customer = new Customer();
                $customer->name = '-';
                $customer->phoneNumber = '-';
                $product_transaction->customer = $customer;
            } else {
                $product_transaction->customer = $customer;
            }

            $product_transaction_details = ProductTransactionDetail::where('ProductTransaction_id', $product_transaction->id)->get();
            foreach($product_transaction_details as $product_transaction_detail) {
                $product = Product::find($product_transaction_detail->Products_id, ['id', 'productName', 'productPrice', 'meassurement']);
                $product_transaction_detail->product = $product;
            }
            $product_transaction->productTransactionkDetails = $product_transaction_details;
        }

        return response()->json([
            "message" => "Get data success",
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

    /**
     * @OA\Put(
     *     path="/api/v1/producttransaction/kasir/updatedetailbyid",
     *     tags={"product transaction"},
     *     summary="Update a detail in product transaction",
     *     @OA\Response(
     *         response=400,
     *         description="Product is null or fails to save or product transaction detail fails to save"
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
     *                     description="The id of the product transaction detail",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="Products_id",
     *                     description="The id of the product",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="itemQty",
     *                     description="The new quantity of the detail",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the product transaction detail",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function updateDetailById(Request $request) {

        $this->validate($request, [
            'id' => 'required|numeric',
            'updatedBy' => 'required|numeric',
            'Products_id' => 'required | numeric',
            'itemQty' => 'required|numeric'
        ]);

        $transaction_detail = ProductTransactionDetail::find($request->id);

        if($transaction_detail != null) {
            $oldQty = $transaction_detail->itemQty;
            $newQty = $request->itemQty;
            $diffQty = abs($oldQty - $newQty);

            $product = Product::find($transaction_detail->Products_id);
            $product_transaction = ProductTransaction::find($transaction_detail->ProductTransaction_id);
            
            $oldSubTotal = $oldQty * $product->productPrice;
            $newSubTotal = $newQty * $product->productPrice;
            $diffSubTotal = abs($oldSubTotal - $newSubTotal);

            if($product != null || $product_transaction != null) {
                if($oldQty >= $newQty) {
                    $product->productQuantity += $diffQty;
                    $product_transaction->total -= $diffSubTotal;
                } else if($oldQty < $newQty) {
                    $productQty = $product->productQuantity;
                    $product_transaction->total += $diffSubTotal;
                    
                    if($productQty - $diffQty < 0) {
                        return response()->json([
                            "message" => "Update gagal",
                            "data" =>[]
                        ], 400);
                    }
                     
                    $product->productQuantity-= $diffQty;
                }

                $product->updatedBy = $request->updatedBy;

                if(!$product_transaction->save()) {
                    return response()->json([
                        "message" => "Update gagal",
                        "data" =>[]
                    ], 400);
                }

                if(!$product->save()) {
                    return response()->json([
                        "message" => "Update gagal",
                        "data" =>[]
                    ], 400);
                }

                event(new ProductMinReached($product));
                
                $transaction_detail->itemQty = $newQty;
                $transaction_detail->updatedBy = $request->updatedBy;

                if($transaction_detail->save()) {
                    return response()->json([
                        "message" => "Update berhasil",
                        "data" =>[$transaction_detail]
                    ], 200);
                }
            }
        }

        return response()->json([
            "message" => "Update gagal",
            "data" =>[]
        ], 400);
    }
    
    /**
     * @OA\Delete(
     *     path="/api/v1/producttransaction/kasir/deletedetailbyid/{id}/{cashierId}",
     *     tags={"product transaction"},
     *     summary="Deletes a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product transaction detail id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="cashierId",
     *         in="path",
     *         description="Cashier who delted the product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Product transaction detail not deleted it's because either the deletion failed or not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
    public function deleteDetailById($id, $cashierId) {
        $transaction_detail = ProductTransactionDetail::find($id);

        if($transaction_detail != null) {
            
            $product = Product::find($transaction_detail->Products_id);
            $product_transaction = ProductTransaction::find($transaction_detail->ProductTransaction_id);
            
            if($product != null || $product_transaction != null) {
                $subTotal = $transaction_detail->itemQty * $product->productPrice;

                $product->productQuantity += $transaction_detail->itemQty;
                $product->updatedBy = $cashierId;
                $product_transaction->total -= $subTotal;
                $product_transaction->updatedBy = $cashierId;

                if(!$product_transaction->save()) {
                    return response()->json([
                        "message" => "Penghapusan gagal",
                        "data" =>[]
                    ], 400);
                }

                if(!$product->save()) {
                    return response()->json([
                        "message" => "Penghapusan gagal",
                        "data" =>[]
                    ], 400);
                }

                event(new ProductMinReached($product));
                
                if($transaction_detail->delete()) {
                    return response()->json([
                        "message" => "Penghapusan berhasil",
                        "data" =>[]
                    ], 200);
                }
            }
        }

        return response()->json([
            "message" => "Penghapusan gagal",
            "data" =>[]
        ], 400);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/producttransaction/kasir/deletetransactionbyid/{id}/{cashierId}",
     *     tags={"product transaction"},
     *     summary="Deletes a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product transaction id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="string"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="cashierId",
     *         in="path",
     *         description="Cashier who deleted the product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Product transaction not deleted it's because either the deletion failed or not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
    public function deleteTransactionById($id, $cashierId) {
        $product_transaction = ProductTransaction::find($id);

        if($product_transaction != null) {
            $transaction_details = $product_transaction->productTransactionDetails()->get();

            foreach($transaction_details as $transaction_detail) {
                $product = Product::find($transaction_detail->Products_id);

                if($product != null) {
                    $product->productQuantity += $transaction_detail->itemQty;
                    $product->updatedBy = $cashierId;

                    if(!$product->save()) {
                        return response()->json([
                            "message" => "Penghapusan gagal",
                            "data" =>[]
                        ], 400);
                    }
                } else {
                    return response()->json([
                        "message" => "Penghapusan gagal",
                        "data" =>[]
                    ], 400);
                }
            }

            if($product_transaction->delete()) {
                return response()->json([
                    "message" => "Penghapusan berhasil",
                    "data" =>[]
                ], 200);
            }
        }

        return response()->json([
            "message" => "Penghapusan gagal",
            "data" =>[]
        ], 400);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/producttransaction/kasir/confirm",
     *     tags={"product transaction"},
     *     summary="Confirm a product transaction",
     *     @OA\Response(
     *         response=400,
     *         description="Product is null or fails to save or product transaction detail fails to save"
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
     *                     description="The id of the product transaction detail",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="total",
     *                     description="The id of the product",
     *                     type="double",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the product transaction detail",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function confirm(Request $request) {
        
        $this->validate($request, [
            'id' => 'required',
            'updatedBy' => 'required|numeric',
            'total' => 'required | numeric'
        ]);

        $product_transaction = ProductTransaction::find($request->id);

        if($product_transaction != null) {
            $product_transaction->total = $request->total;
            $product_transaction->updatedBy = $request->updatedBy;
            $product_transaction->isPaid = 1;

            if($product_transaction->save()) {
                return response()->json([
                    "message" => "Konfirmasi pesanan berhasil",
                    "data" =>[]
                ], 200);
            }
        }

        return response()->json([
            "message" => "Konfirmasi pesanan gagal",
            "data" =>[]
        ], 400);
    }
}
?>