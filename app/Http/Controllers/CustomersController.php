<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomersController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/customers/getall",
	*	  tags={"customers"},
    *     description="Get all customers",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all customers")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => Customer::all()
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/customers/insert",
     *     tags={"customers"},
     *     summary="Insert a new customer",
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
     *                     property="createdBy",
     *                     description="The foreign key of the owner who creates the customer",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'address' => 'required',
            'dateBirth' => 'required|date',
            'phoneNumber' => 'required|numeric',
            'createdBy' => 'required',
        ]);

        $customer = new Customer;
        $customer->name = $request->name;
        $customer->address = $request->address;
        $customer->dateBirth = $request->dateBirth;
        $customer->phoneNumber = $request->phoneNumber;
        $customer->createdBy = $request->createdBy;

        if($customer->save()) {
            return response()->json([
                "message" => "Customer created",
                "data" => $customer
            ], 200);
        } else {
            return response()->json([
                "message" => "Customer not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/customers/getbyid/{id}",
	*	  tags={"customers"},
    *     description="Get a customer by id",
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
    *     @OA\Response(response="default", description="Get a customer by id")
    * ),
    */
    public function getCustomerById($id) {
        $customer = Customer::find($id);

        if($customer) {
            return response()->json([
                "message" => "Success",
                "data" => $customer
            ], 200);
        } else {
            return response()->json([
                "message" => "Customer not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Post(
     *     path="/api/v1/customers/update",
     *     tags={"customers"},
     *     summary="Update a customer",
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
     *                     description="The foreign key of the owner who updates the customer",
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

		$customer = Customer::find($request->id);
		if($customer) {
			$customer->name = $request->name;
			$customer->address = $request->address;
			$customer->dateBirth = $request->dateBirth;
			$customer->phoneNumber = $request->phoneNumber;
			$customer->updatedBy = $request->updatedBy;

			if($customer->save()) {
				return response()->json([
					"message" => "Customer updated",
					"data" => $customer
				], 200);
			}
		}
        return response()->json([
            "message" => "Customer not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/customers/delete/{id}/{ownerId}",
     *     tags={"customers"},
     *     summary="Deletes a customer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Customer id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who delted the customer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Customer not deleted it's because either the deletion failed or customer to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$customer = Customer::find($id);
		
		if($customer->delete()) {
			$customer->deletedBy = $ownerId;
			$customer->save();
			return response()->json([
				"message" => "Customer deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Customer not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/customers/restore/{id}",
	*	  tags={"customers"},
	*     description="Restore the delted customer",
	*	  security={
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
    *     @OA\Response(response="default", description="Restore the deleted customer")
    * ),
    */
	public function restore($id) {
		$customer = Customer::onlyTrashed()->where('id', $id);
		
		if($customer) {
			$customer->restore();
			$customer = Customer::find($id);
			$customer->deletedBy = NULL;
			$customer->save();

			return response()->json([
				"message" => "Customer restored",
				"data" => $customer
			], 200);
		} else {
			return response()->json([
				"message" => "Customer not restored",
				"data" => []
			], 400);
		}
	}

    /**
        * @OA\Get(
        *     path="/api/v1/customers/getallpets/{id}",
        *	  tags={"customers"},
        *     description="Get all of the customer's pets by customer id",
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
        *     @OA\Response(response="default", description="All of customer's pets")
        * ),
        */
        public function getAllCustomerPetsByCustomerId($id) {
            $customer = Customer::find($id);

            if($customer) {
                return response()->json([
                    "message" => "Success",
                    "data" => $customer->pets()->get()
                ], 200);
            } else {
                return response()->json([
                    "message" => "Customer not found",
                    "data" => []
                ], 400);
            }
        }
}
?>