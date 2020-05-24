<?php

namespace App\Http\Controllers;

use App\ServiceTransaction;
use App\ServiceTransactionDetail;
use App\ProductTransactionDetail;
use App\ProductRestockDetail;
use App\ServiceDetail;
use App\Employee;
use App\Customer;
use App\Pet;
use App\Service;
use App\PetType;
use App\PetSize;
use App\Product;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller {
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/bestsellingservice/{this_year}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get all the best selling service",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year of service",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the best selling service of the year by month")
    * ),
    */
    public function bestSellingService($this_year) {
        $services = ServiceTransactionDetail::all();

        $service_month = $this->divideServicesBasedOnMonth($services, (string) $this_year);

        $arr_report = array();

        for($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 10));

            if(!isset($service_month[$i])) {
                $arr_report[$month] = array(
                    "service" => "",
                    "count" => 0
                );
            } else {
                $value = $service_month[$i];
                $most_frequent = $this->mostFrequentService($value, sizeof($value));
                $service_detail = ServiceDetail::find($most_frequent["service"]);
                $service = Service::find($service_detail->Services_id)->serviceName;
                $type = PetType::find($service_detail->PetTypes_id)->type;
                $size = PetSize::find($service_detail->PetSizes_id)->size;

                $most_frequent["service"] = "$service $type $size";
                $arr_report[$month] = $most_frequent;
            }
        }

        return response()->json($arr_report);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/bestsellingproduct/{this_year}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get all the best selling product",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year of product",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the best selling product of the year by month")
    * ),
    */
    public function bestSellingProduct($this_year) {
        $product = ProductTransactionDetail::all();

        $product_month = $this->divideProductsBasedOnMonth($product, (string) $this_year);
        $arr_report = array();

        for($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 10));

            if(!isset($product_month[$i])) {
                $arr_report[$month] = array(
                    "product" => "",
                    "count" => 0
                );
            } else {
                $value = $product_month[$i];
                $most_frequent = $this->mostFrequentProduct($value, sizeof($value));

                $product = Product::find($most_frequent["product"]);

                $most_frequent["product"] = $product->productName;
                $arr_report[$month] = $most_frequent;
            }
        }

        return response()->json($arr_report);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/yearlyincome/{this_year}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get the yearly income",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the yearly income")
    * ),
    */
    public function yearlyIncome($this_year) {
        $products = ProductTransactionDetail::all();
        $services = ServiceTransactionDetail::all();
        

        $product_month = $this->divideProductsBasedOnMonth($products, (string) $this_year);
        $service_month = $this->divideServicesBasedOnMonth($services, (string) $this_year);
        $arr_report = array();
        $total = 0;

        for($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 10));

            if(!isset($product_month[$i]) || !isset($service_month[$i])) {
                $arr_report[$month] = array(
                    "service" => 0,
                    "product" => 0,
                    "sub_total" => 0
                );
            } else {
                $total_service = 0;
                $total_product = 0;

                $product_report = $product_month[$i];
                $service_report = $service_month[$i];
                
                foreach($service_report as $item) {
                    $service_detail = ServiceDetail::find($item);
                    $total_service += $service_detail->price;
                }

                foreach($product_report as $key => $value) {
                    $product = Product::find($key);
                    $total_product += $product->productPrice * $value;
                }
                
                $sub_total = $total_service + $total_product;
                $total += $sub_total;

                $arr_report[$month] = array(
                    "service" => $total_service,
                    "product" => $total_product,
                    "sub_total" => $sub_total
                );
            }
        }

        return response()->json([
            "report" => $arr_report,
            "total" => $total
        ]);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/monthlyincome/{this_year}/{this_month}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get the montly income",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Parameter(
    *         name="this_month",
    *         in="path",
    *         description="Month",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the monthly income")
    * ),
    */
    public function monthlyIncome($this_year, $this_month) {
        $products = ProductTransactionDetail::all();
        $services = ServiceTransactionDetail::all();
        

        $product_month = $this->getProductBasedOnMonth($products, (string) $this_year, $this_month);
        $service_month = $this->getServiceBasedOnMonth($services, (string) $this_year, $this_month);
        $services_grouped = $this->groupingArrayTransaction($service_month);
        $product_grouped = $this->groupingArrayTransaction($product_month);
        $arr_report = array();
        $arr_report["services"]= array();
        $arr_report["services"]["service_total"] = 0;
        
        $arr_report["products"]= array();
        $arr_report["products"]["product_total"] = 0;

        foreach($services_grouped as $key => $value) {
            $service_detail = ServiceDetail::find($key);
            $service = Service::find($service_detail->Services_id)->serviceName;
            $type = PetType::find($service_detail->PetTypes_id)->type;
            $size = PetSize::find($service_detail->PetSizes_id)->size;
            $service_name = "$service $type $size";

            array_push($arr_report["services"], [
                "name" => $service_name,
                "sub_total" => $value
            ]);

            $arr_report["services"]["service_total"] += $service_detail->price;
        }

        foreach($product_grouped as $key => $value) {
            $product = Product::find($key);

            array_push($arr_report["products"], [
                "name" => $product->productName,
                "sub_total" => $value
            ]);

            $arr_report["products"]["product_total"] += $value;
        }

        if(!isset($arr_report['services'][0])) {
            $arr_report['services'][0] = array(
                "name" => "",
                "sub_total" => 0
            );
        }

        if(!isset($arr_report['products'][0])) {
            $arr_report['products'][0] = array(
                "name" => "",
                "sub_total" => 0
            );
        }

        return response()->json($arr_report);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/yearlyrestockproduct/{this_year}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get the yearly restock report",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the yearly restock report")
    * ),
    */
    public function yearlyRestockProduct($this_year) {
        $restock = ProductRestockDetail::all();
        

        $restock_month = $this->divideProductRestockBasedOnMonth($restock, (string) $this_year);

        $arr_report = array();
        $total = 0;

        for($i = 1; $i <= 12; $i++) {
            $month = date('F', mktime(0, 0, 0, $i, 10));

            if(!isset($restock_month[$i])) {
                $arr_report[$month] = array(
                    "outcome" => 0
                );
            } else {
                $total_outcome = 0;

                $restock_report = $restock_month[$i];

                foreach($restock_report as $key => $value) {
                    $product = Product::find($key);
                    $total_outcome += $product->productPrice * $value;
                }

                $total += $total_outcome;

                $arr_report[$month] = array(
                    "outcome" => $total_outcome
                );
            }
        }

        return response()->json([
            "report" => $arr_report,
            "total" => $total
        ]);
    }

    /**
    * @OA\Get(
	*     path="/api/v1/report/monthlyrestockproduct/{this_year}/{this_month}",
	*	  tags={"report"},
    *     security={
    *         {"bearerAuth": {}}
    *     },
    *     description="Get the montly restock product outcome",
    *     @OA\Parameter(
    *         name="this_year",
    *         in="path",
    *         description="Year",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Parameter(
    *         name="this_month",
    *         in="path",
    *         description="Month",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         ),
    *     ),
    *     @OA\Response(response="default", description="Return the monthly restock product outcome")
    * ),
    */
    public function monthlyRestockProduct($this_year, $this_month) {
        $products = ProductRestockDetail::all();
        

        $product_month = $this->getProductRestockBasedOnMonth($products, (string) $this_year, $this_month);
        $product_grouped = $this->groupingArrayTransaction($product_month);
        $arr_report = array();
        $arr_report["products"] = array();
        $arr_report["total"] = 0;
        $total = 0;

        foreach($product_grouped as $key => $value) {
            $product = Product::find($key);

            array_push($arr_report["products"], [
                "name" => $product->productName,
                "sub_total" => $value
            ]);

            $arr_report["total"] += $value;
        }

        if(!isset($arr_report['products'][0])) {
            $arr_report['products'][0] = array(
                "name" => "",
                "sub_total" => 0
            );
        }

        return response()->json($arr_report);
    }

    private function groupingArrayTransaction($arr) {
        $hash = array();

        foreach($arr as $item) {
            if(!isset($hash[$item['id']])) {
                $hash[$item['id']] = $item['price'];
            } else {
                $hash[$item['id']] += $item['price'];
            }
        }

        return $hash;
    }

    private function mostFrequentService($arr, $n) {
        $hash = array();

        for($i = 0; $i < $n; $i++) {
            if(!isset($hash[$arr[$i]])) {
                $hash[$arr[$i]] = 1;
            } else {
                $hash[$arr[$i]]++;
            }
        }

        $max_count = 0;
        $res = -1;
        foreach($hash as $key => $item) {
            if($max_count < $item) {
                $res = $key;
                $max_count = $item;
            }
        }

        return array(
            "service" => $res,
            "count" => $max_count
        );
    }

    private function mostFrequentProduct($arr, $n) {
        $hash = array();

        foreach($arr as $key => $value) {
            if(!isset($hash[$key])) {
                $hash[$key] = $value;
            } else {
                $hash[$key] += $value;
            }
        }

        $max_count = 0;
        $res = -1;
        foreach($hash as $key => $item) {
            if($max_count < $item) {
                $res = $key;
                $max_count = $item;
            }
        }

        return array(
            "product" => $res,
            "count" => $max_count
        );
    }

    private function divideServicesBasedOnMonth($detail, $this_year) {
        $arr_months = array();

        foreach($detail as $item) {
            $timestamp = $item['createdAt'];
            $year = Carbon::parse($timestamp)->format('Y');
            
            if($this_year === $year) {
                $month = (int) Carbon::parse($timestamp)->format('m');
                if(!isset($arr_months[$month])) {
                    $arr_months[$month] = array($item['ServiceDetails_id']);
                } else {
                    $arr_item = $arr_months[$month];
                    array_push($arr_item, $item['ServiceDetails_id']);
                    $arr_months[$month] = $arr_item;
                }
            }
        }

        return $arr_months;
    }

    private function getServiceBasedOnMonth($detail, $this_year, $this_month) {
        $arr_services = array();

        foreach($detail as $item) {
            $timestamp = $item['createdAt'];
            $year = Carbon::parse($timestamp)->format('Y');
            $month = (int) Carbon::parse($timestamp)->format('m');
            $service_detail = ServiceDetail::find($item['ServiceDetails_id']);
            if($this_year === $year && (int) $this_month === $month) {
                array_push($arr_services, [
                    "id" => $item['ServiceDetails_id'],
                    "price" => $service_detail->price
                ]);
            }
        }

        return $arr_services;
    }

    private function getProductBasedOnMonth($detail, $this_year, $this_month) {
        $arr_product = array();

        foreach($detail as $item) {
            $timestamp = $item['createdAt'];
            $year = Carbon::parse($timestamp)->format('Y');
            $month = (int) Carbon::parse($timestamp)->format('m');
            $product = Product::find($item['Products_id']);
            if($this_year === $year && (int) $this_month === $month) {
                array_push($arr_product, [
                    "id" => $item['Products_id'],
                    "price" => $product->productPrice * $item->itemQty
                ]);
            }
        }

        return $arr_product;
    }

    private function getProductRestockBasedOnMonth($detail, $this_year, $this_month) {
        $arr_product = array();

        foreach($detail as $item) {
            $timestamp = $item['created_at'];
            $year = Carbon::parse($timestamp)->format('Y');
            $month = (int) Carbon::parse($timestamp)->format('m');
            $product = Product::find($item['Products_id']);
            if($this_year === $year && (int) $this_month === $month) {
                array_push($arr_product, [
                    "id" => $item['Products_id'],
                    "price" => $product->productPrice * $item->itemQty
                ]);
            }
        }

        return $arr_product;
    }

    private function divideProductsBasedOnMonth($detail, $this_year) {
        $arr_months = array();

        foreach($detail as $item) {
            $timestamp = $item['createdAt'];
            $year = Carbon::parse($timestamp)->format('Y');
            
            if($this_year === $year) {
                $month = (int) Carbon::parse($timestamp)->format('m');
                if(!isset($arr_months[$month])) {
                    $arr_months[$month] = array(
                        $item['Products_id'] => $item['itemQty']
                    );
                } else {
                    $arr_item = $arr_months[$month];
            
                    if(!isset($arr_item[$item['Products_id']])) {
                        $arr_item[$item['Products_id']] = $item['itemQty'];    
                    } else {
                        $arr_item[$item['Products_id']] += $item['itemQty'];            
                    }
            
                    $arr_months[$month] = $arr_item;
                }
            }
        }

        return $arr_months;
    }

    private function divideProductRestockBasedOnMonth($detail, $this_year) {
        $arr_months = array();

        foreach($detail as $item) {
            $timestamp = $item['created_at'];
            $year = Carbon::parse($timestamp)->format('Y');
            
            if($this_year === $year) {
                $month = (int) Carbon::parse($timestamp)->format('m');
                if(!isset($arr_months[$month])) {
                    $arr_months[$month] = array(
                        $item['Products_id'] => $item['itemQty']
                    );
                } else {
                    $arr_item = $arr_months[$month];
            
                    if(!isset($arr_item[$item['Products_id']])) {
                        $arr_item[$item['Products_id']] = $item['itemQty'];    
                    } else {
                        $arr_item[$item['Products_id']] += $item['itemQty'];            
                    }
            
                    $arr_months[$month] = $arr_item;
                }
            }
        }

        return $arr_months;
    }
}