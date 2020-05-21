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
}
?>