<?php

namespace App\Http\Controllers;

use App\Customer;
use App\ProductRestockDetail;
use App\Pet;
use App\ServiceDetail;
use App\Service;
use App\PetType;
use App\Employee;
use App\PetSize;
use App\Product;
use App\FCMModel;
use App\ProductRestock;
use App\Supplier;
use App\ServiceTransactionDetail;
use App\ProductTransactionDetail;
use App\ProductTransaction;
use App\ServiceTransaction;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class LogController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/productrestockdetail",
	*	  tags={"log"},
    *     description="Get all Product Restock Detail Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Product Restock Detail Log")
    * ),
    */
    public function productRestockDetail() {
        $datas = ProductRestockDetail::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/log/product",
	*	  tags={"log"},
    *     description="Get all Product Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Product Log")
    * ),
    */
    public function product() {
        $datas = Product::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/log/productrestock",
	*	  tags={"log"},
    *     description="Get all Product Restock Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Product Restock Log")
    * ),
    */
    public function productRestock() {
        $datas = ProductRestockDetail::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/supplier",
	*	  tags={"log"},
    *     description="Get all Supplier Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Supplier Log")
    * ),
    */
    public function supplier() {
        $datas = Supplier::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/producttransactiondetail",
	*	  tags={"log"},
    *     description="Get all Product Transaction Detail Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Product Transaction Detail Log")
    * ),
    */
    public function productTransactionDetail() {
        $datas = ProductTransactionDetail::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/employee",
	*	  tags={"log"},
    *     description="Get all Employee Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Employee Log")
    * ),
    */
    public function employee() {
        $datas = Employee::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/fcm",
	*	  tags={"log"},
    *     description="Get all FCM Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All FCM Log")
    * ),
    */
    public function fcm() {
        $datas = FCMModel::withTrashed()->get();

        if($datas != null) {
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/servicetransactiondetail",
	*	  tags={"log"},
    *     description="Get all Service Transaction Detail Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Service Transaction Detail Log")
    * ),
    */
    public function serviceTransactionDetail() {
        $datas = ServiceTransactionDetail::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/servicetransaction",
	*	  tags={"log"},
    *     description="Get all Service Transaction Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Service Transaction Log")
    * ),
    */
    public function serviceTransaction() {
        $datas = ServiceTransaction::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/customer",
	*	  tags={"log"},
    *     description="Get all Customer Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Customer Log")
    * ),
    */
    public function customer() {
        $datas = Customer::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/pet",
	*	  tags={"log"},
    *     description="Get all Pet Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Pet Log")
    * ),
    */
    public function pet() {
        $datas = Pet::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/servicedetail",
	*	  tags={"log"},
    *     description="Get all Service Detail Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Service Detail Log")
    * ),
    */
    public function serviceDetail() {
        $datas = ServiceDetail::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/service",
	*	  tags={"log"},
    *     description="Get all Service Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Service Log")
    * ),
    */
    public function service() {
        $datas = Service::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }

                $deletor = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($deletor != null) {
                    $data->deletor = $deletor;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->deletor = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/petsize",
	*	  tags={"log"},
    *     description="Get all Pet Size Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Pet Size Log")
    * ),
    */
    public function petSize() {
        $datas = PetSize::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/log/pettype",
	*	  tags={"log"},
    *     description="Get all Pet Type Log",
    *     security={
    *     {"bearerAuth": {}},
    *   },
    *     @OA\Response(response="default", description="All Pet Type Log")
    * ),
    */
    public function petType() {
        $datas = PetType::withTrashed()->get();

        if($datas != null) {
            foreach($datas as $data) {
                $creator = Employee::withTrashed()->find($data->createdBy, ['id', 'name']);


                if($creator != null) {
                    $data->creator = $creator;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->creator = $employee;
                }

                $updater = Employee::withTrashed()->find($data->updatedBy, ['id', 'name']);

                if($updater != null) {
                    $data->updater = $updater;
                } else {
                    $employee = new Employee();
                    $employee->id = -1;
                    $employee->name = "";

                    $data->updater = $employee;
                }
            }
            
            return response()->json([
                "message" => "success", 
                "data" => $datas
            ], 200);
        }
        

        return response()->json([
            "message" => "failed", 
            "data" => []
        ], 400);
    }
}
?>