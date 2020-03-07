<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SuppliersController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/suppliers/getall",
	*	  tags={"suppliers"},
    *     description="Get all suppliers",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all suppliers")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => Supplier::all()
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/suppliers/insert",
     *     tags={"suppliers"},
     *     summary="Insert a new supplier",
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
     *                     description="The name of the supplier",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of the supplier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phoneNumber",
     *                     description="The phone number of the supplier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The foreign key of the owner who creates the supplier",
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
            'phoneNumber' => 'required|numeric',
            'createdBy' => 'required',
        ]);

        $supplier = new Supplier;
        $supplier->name = $request->name;
        $supplier->address = $request->address;
        $supplier->phoneNumber = $request->phoneNumber;
        $supplier->createdBy = $request->createdBy;

        if($supplier->save()) {
            return response()->json([
                "message" => "Supplier created",
                "data" => $supplier
            ], 200);
        } else {
            return response()->json([
                "message" => "Supplier not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/suppliers/getbyid/{id}",
	*	  tags={"suppliers"},
    *     description="Get an supplier by id",
    *     security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of supplier",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a supplier by id")
    * ),
    */
    public function getSupplierById($id) {
        $supplier = Supplier::find($id);

        if($supplier) {
            return response()->json([
                "message" => "Success",
                "data" => $supplier
            ], 200);
        } else {
            return response()->json([
                "message" => "Supplier not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Post(
     *     path="/api/v1/suppliers/update",
     *     tags={"suppliers"},
     *     summary="Update a supplier",
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
     *                     description="The id of the supplier",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of the supplier",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of the supplier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phoneNumber",
     *                     description="The phone number of the supplier",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the supplier",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request) {

        $this->validate($request, [
            'phoneNumber' => 'numeric',
        ]);

		$supplier = Supplier::find($request->id);
		if($supplier) {
			$supplier->name = $request->name;
			$supplier->address = $request->address;
			$supplier->phoneNumber = $request->phoneNumber;
			$supplier->updatedBy = $request->updatedBy;

			if($supplier->save()) {
				return response()->json([
					"message" => "Supplier updated",
					"data" => $supplier
				], 200);
			}
		}
        return response()->json([
            "message" => "Supplier not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/suppliers/delete/{id}/{ownerId}",
     *     tags={"suppliers"},
     *     summary="Deletes an supplier",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Supplier id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who delted the supplier",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Supplier not deleted it's because either the deletion failed or supplier to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$supplier = Supplier::find($id);
		
		if($supplier->delete()) {
			$supplier->deletedBy = $ownerId;
			$supplier->save();
			return response()->json([
				"message" => "Supplier deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Supplier not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/suppliers/restore/{id}",
	*	  tags={"suppliers"},
	*     description="Restore the delted supplier",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of supplier",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restored the deleted supplier")
    * ),
    */
	public function restore($id) {
		$supplier = Supplier::onlyTrashed()->where('idSupplier', $id);
		
		if($supplier) {
			$supplier->restore();
			$supplier = Supplier::find($id);
			$supplier->deletedBy = NULL;
			$supplier->save();

			return response()->json([
				"message" => "Supplier restored",
				"data" => $supplier
			], 200);
		} else {
			return response()->json([
				"message" => "Supplier not restored",
				"data" => []
			], 400);
		}
	}
}
?>