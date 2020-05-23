<?php

namespace App\Http\Controllers;

use App\ServiceTransaction;
use App\ServiceTransactionDetail;
use App\ProductTransactionDetail;
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
        $services = ProductTransactionDetail::all();

        $product_month = $this->divideProductsBasedOnMonth($services, (string) $this_year);
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
}