<?php

namespace App\Http\Controllers;

use App\ServiceTransaction;
use App\ServiceTransactionDetail;
use App\ServiceDetail;
use App\Employee;
use App\Customer;
use App\Pet;
use App\Service;
use App\PetType;
use App\PetSize;
use App\Events\ProductMinReached;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ServiceTransactionController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/servicetransaction/kasir/getall",
	*	  tags={"service transaction"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get all the service transaction",
    *     @OA\Response(response="default", description="Service transaction and its detail")
    * ),
    */
    public function getAll() {
        $service_transactions = ServiceTransaction::where('isPaid', 0)->get();
    
        if(!$service_transactions) {
            return response()->json([
                "message" => "Get data error",
                "data" => []
            ], 400);
        }

        foreach($service_transactions as $service_transaction) {
            $service_transactions->cs_name = Employee::find($service_transaction->createdBy)->name;
            $pet = Pet::find($service_transaction->Pets_id, ['id', 'name', 'PetSizes_id', 'PetTypes_id']);
            $service_transaction->pet = $pet;
            $service_transaction->customer = Customer::find($pet->Customers_id, ['id', 'name', 'phoneNumber']);

            $service_transaction_details = ServiceTransactionDetail::where('ServiceTransaction_id', $service_transaction->id)->get();
            foreach($service_transaction_details as $service_transaction_detail) {
                $service_detail = ServiceDetail::find($product_transaction_detail->ServiceDetails_id, ['id', 'price', 'PetTypes_id', 'PetSizes_id', 'Services_id']);
                $service_name = Service::find($service_detail->Services_id)->serviceName;
                $pet_type = PetType::find($service_detail->PetTypes_id)->type;
                $pet_size = PetSize::find($service_detail->PetSizes_id)->size;
                $service_detail->complete_name = "$service_name $pet_type $pet_size";
                $service_transaction_detail->service = $service_detail;
            }
            $service_transaction->service_detail = $service_transaction_details;
        }

        return response()->json([
            "message" => "Get data success",
            "data" => $service_transactions
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/servicetransaction/cs/insert",
     *     tags={"service transaction"},
     *     summary="Insert a new service transaction",
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
     *                     property="serviceTransactionDetails",
     *                     description="Array of Service Transaction Detail",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The id of the cs",
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
            'serviceTransactionDetails' => 'required',
            'total' => 'required|numeric'
        ]);

        $serviceTransactionDetails = $request->serviceTransactionDetails;

        $latest_id = ServiceTransaction::latest()->withTrashed()->first();
        $current_id;
        $date = Carbon::parse(Carbon::now()->toDateString())->format('dmy');
        if ($latest_id == null) {
            $current_id = 'LY-'.$date.'-'.'00';    
        } else {
            $current_id = 'LY-'.$date.'-'.sprintf("%02d", substr($latest_id->id, strrpos($latest_id->id, '-') + 1) + 1);
        }

        $service_transaction = new ServiceTransaction;
        $service_transaction->id = $current_id;
        $service_transaction->createdBy = $request->createdBy;
        $service_transaction->isPaid = 0;
        $service_transaction->total = $request->total;
        $service_transaction->Pets_id = $request->Pets_id;


        if($service_transaction->save()) {
            foreach($serviceTransactionDetails as $item) {
                $service_transaction_detail = new ServiceTransactionDetail;
                $service_transaction_detail->ServiceDetails_id = $item['ServiceDetails_id'];
                $service_transaction_detail->createdBy = $item['createdBy'];
                $service_transaction_detail->ServiceTransaction_id = $current_id;
                $service_transaction_detail->isFinished = 0;
                
                $product_transaction_detail->save();
            }

         
            return response()->json([
                "message" => "Service transaction created",
                "data" => [$service_transaction]
            ], 200);
        }

        return response()->json([
            "message" => "Service transaction not created",
            "data" => []
        ], 400);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/servicetransaction/kasir/updatedetailbyid",
     *     tags={"service transaction"},
     *     summary="Update a detail in service transaction",
     *     @OA\Response(
     *         response=400,
     *         description="Service detail is null or fails to save or service transaction detail fails to save"
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
     *                     description="The id of the service transaction detail",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="new_service_detail_id",
     *                     description="The new id of the service detail",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the cashier who updates the service transaction detail",
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
            'new_service_detail_id' => 'required|numeric'
        ]);

        $transaction_detail = ServiceTransactionDetail::find($request->id);

        if($transaction_detail != null) {
            $service_detail = ServiceDetail::find($transaction_detail->ServiceDetails_id);
            $new_service_detail = ServiceDetail::find($request->new_service_detail_id);
            $service_transaction = ServiceTransaction::find($transaction_detail->ServiceTransaction_id);

            if($service_detail != null || $new_service_detail != null || $service_transaction != null) {
                $service_transaction->total -= $service_detail->price;
                $service_transaction->total += $service_detail->price;
                
                if(!$service_transaction->save()) {
                    return response()->json([
                        "message" => "Update gagal",
                        "data" =>[]
                    ], 400);
                }

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
     *     path="/api/v1/servicetransaction/kasir/deletedetailbyid/{id}/{cashierId}",
     *     tags={"service transaction"},
     *     summary="Deletes a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Service transaction detail id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
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
     *         description="Service transaction detail not deleted it's because either the deletion failed or not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
    public function deleteDetailById($id, $cashierId) {
        $transaction_detail = ServiceTransactionDetail::find($id);

        if($transaction_detail != null) {
            
            $service_detail = ServiceDetail::find($transaction_detail->ServiceDetails_id);
            $service_transaction = ServiceTransaction::find($transaction_detail->ServiceTransaction_id);
            
            if($service_detail != null || $service_transaction != null) {
                $service_transaction->total -= $service_detail->price;
                $service_transaction->updatedBy = $cashierId;

                if(!$service_transaction->save()) {
                    return response()->json([
                        "message" => "Penghapusan gagal",
                        "data" =>[]
                    ], 400);
                }
                
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
     *     path="/api/v1/servicetransaction/kasir/deletetransactionbyid/{id}",
     *     tags={"service transaction"},
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
     *     @OA\Response(
     *         response=400,
     *         description="Product transaction not deleted it's because either the deletion failed or not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
    public function deleteTransactionById($id) {
        $service_transaction = ServiceTransaction::find($id);

        if($service_transaction != null) {
            if($service_transaction->delete()) {
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
}
?>