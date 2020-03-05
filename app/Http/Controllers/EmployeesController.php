<?php

namespace App\Http\Controllers;

use App\Employees;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmployeesController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/employees/getall",
	*	  tags={"employees"},
    *     description="Get all employees",security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="Get all employees")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => Employees::all()
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/employees/insert",
     *     tags={"employees"},
     *     summary="Insert a new employee",
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
     *                     description="The name of the employee",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="dateBirth",
     *                     description="The birth date of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phoneNumber",
     *                     description="The phone number of the emploeyee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     description="The role of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     description="The username of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="The password of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The foreign key of the owner who creates the employee",
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
            'role' => 'required',
            'username' => 'required',
            'password' => 'required',
            'createdBy' => 'required',
        ]);

        $employee = new Employees;
        $employee->name = $request->name;
        $employee->address = $request->address;
        $employee->dateBirth = $request->dateBirth;
        $employee->phoneNumber = $request->phoneNumber;
        $employee->role = $request->role;
        $employee->username = $request->username;
        $employee->password = Hash::make($request->password);
        $employee->createdBy = $request->createdBy;

        if($employee->save()) {
            return response()->json([
                "message" => "Employee created",
                "data" => $employee
            ], 200);
        } else {
            return response()->json([
                "message" => "Employee not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/employees/getbyid/{id}",
	*	  tags={"employees"},
    *     description="Get an employee by id",security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of employee",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get an employee by id")
    * ),
    */
    public function getEmployeeById($id) {
        $employee = Employees::find($id);

        if($employee) {
            return response()->json([
                "message" => "Success",
                "data" => $employee
            ], 200);
        } else {
            return response()->json([
                "message" => "Employee not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Post(
     *     path="/api/v1/employees/update",
     *     tags={"employees"},
     *     summary="Update an employee",
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
     *                     description="The id of the employee",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     description="The name of the employee",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="address",
     *                     description="The address of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="dateBirth",
     *                     description="The birth date of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="phoneNumber",
     *                     description="The phone number of the emploeyee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="role",
     *                     description="The role of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="username",
     *                     description="The username of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="The password of the employee",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the employee",
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

		$employee = Employees::find($request->id);
		if($employee) {
			$employee->name = $request->name;
			$employee->address = $request->address;
			$employee->dateBirth = $request->dateBirth;
			$employee->phoneNumber = $request->phoneNumber;
			$employee->role = $request->role;
			$employee->username = $request->username;
			if($employee->password !== $request->password && strlen($request->password) >=0) {
				$employee->password = Hash::make($request->password);
			}
			$employee->updatedBy = $request->updatedBy;

			if($employee->save()) {
				return response()->json([
					"message" => "Employee updated",
					"data" => $employee
				], 200);
			}
		}
        return response()->json([
            "message" => "Employee not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/employees/delete/{id}/{ownderId}",
     *     tags={"employees"},
     *     summary="Deletes an employee",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Employee id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 		   @OA\Parameter(
     *         name="ownderId",
     *         in="path",
     *         description="Owner who delted the employee",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Employee not deleted it's because either the deletion failed or employee to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$employee = Employees::find($id);
		
		if($employee->delete()) {
			$employee->deletedBy = $ownerId;
			$employee->save();
			return response()->json([
				"message" => "Employee deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Employee not deleted",
				"data" => []
			]);
		}
	}
}
?>