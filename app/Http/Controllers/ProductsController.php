<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use finfo;

class ProductsController extends Controller {

    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    /**
    * @OA\Get(
	*     path="/api/v1/noa/products/getall",
	*	  tags={"products"},
    *     description="Get all products",
    *     @OA\Response(response="default", description="Get all products")
    * ),
    */
    public function getAll() {
        return response()->json([
            "message" => "success", 
            "data" => Product::all()->makeHidden(['image'])
        ], 200);
	}

	/**
     * @OA\Post(
     *     path="/api/v1/products/insert",
     *     tags={"products"},
     *     summary="Insert a new product",
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
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="productName",
     *                     description="The name of the product",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="productQuantity",
     *                     description="The quantity of the product",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="productPrice",
     *                     description="The price of the product",
     *                     type="double"
     *                 ),
     *                 @OA\Property(
     *                     property="meassurement",
     *                     description="The meassurement of the product e.g kg, pcs, liter",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="createdBy",
     *                     description="The foreign key of the owner who creates the product",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="The image of the product (65Kb)",
     *                     type="file",
     *                     @OA\Items(type="string", format="binary")
     *                 ),
     *                 @OA\Property(
     *                     property="minimumQty",
     *                     description="The minimum quantity of the product",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function insert(Request $request) {

        $this->validate($request, [
            'productName' => 'required',
            'productQuantity' => 'required|numeric',
            'productPrice' => 'required|numeric',
            'meassurement' => 'required',
            'createdBy' => 'required',
            'minimumQty' => 'required',
            'image' => 'required|mimes:jpeg|max:65',
        ]);

        $product = new Product;
        $product->productName = $request->productName;
        $file = $request->file('image');
        $product->image = $file->openFile()->fread($file->getSize());
        $product->minimumQty = $request->minimumQty;
        $product->productQuantity = $request->productQuantity;
        $product->productPrice = $request->productPrice;
        $product->meassurement = $request->meassurement;
        $product->createdBy = $request->createdBy;

        if($product->save()) {
            return response()->json([
                "message" => "Product created",
                "data" => $product->makeHidden(['image'])
            ], 200);
        } else {
            return response()->json([
                "message" => "Product not created",
                "data" => []
            ], 400);
        }
    }


    /**
    * @OA\Get(
	*     path="/api/v1/noa/products/getbyid/{id}",
	*	  tags={"products"},
    *     description="Get a product by id",
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of products",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a products by id")
    * ),
    */
    public function getProductById($id) {
        $product = Product::find($id);

        if($product) {
            return response()->json([
                "message" => "Success",
                "data" => $product->makeHidden(['image'])
            ], 200);
        } else {
            return response()->json([
                "message" => "Product not found",
                "data" => []
            ], 400);
        }
    }
    
    /**
    * @OA\Get(
	*     path="/api/v1/noa/products/getimagebyid/{id}",
	*	  tags={"products"},
    *     description="Get a product image by id",
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of products",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Get a products by id")
    * ),
    */
    public function getProductImageById($id) {
        $product = Product::find($id);

        if($product) {
            return response()->make($product->image, 200, array(
                'Content-Type' => (new finfo(FILEINFO_MIME))->buffer($product->image)
            ));
        } else {
            return response()->json([
                "message" => "Product not found",
                "data" => []
            ], 400);
        }
	}
	
	 /**
     * @OA\Post(
     *     path="/api/v1/products/update",
     *     tags={"products"},
     *     summary="Update a product",
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
     *                     property="productName",
     *                     description="The name of the product",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="productQuantity",
     *                     description="The quantity of the product",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="productPrice",
     *                     description="The price of the product",
     *                     type="double"
     *                 ),
     *                 @OA\Property(
     *                     property="meassurement",
     *                     description="The meassurement of the product e.g kg, pcs, liter",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="updatedBy",
     *                     description="The foreign key of the owner who updates the product",
     *                     type="integer"
     *                 ),
     *                 @OA\Property(
     *                     property="image",
     *                     description="The image of the product (65Kb)",
     *                     type="string",
     *                     format="binary"
     *                 ),
     *                 @OA\Property(
     *                     property="minimumQty",
     *                     description="The minimum quantity of the product",
     *                     type="integer"
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function update(Request $request) {

        $this->validate($request, [
            'productName' => 'required',
            'productQuantity' => 'required|numeric',
            'productPrice' => 'required|numeric',
            'meassurement' => 'required',
            'createdBy' => 'required',
            'minimumQty' => 'required',
            'image' => 'required|mimes:jpeg|size:65',
        ]);

		$product = Product::find($request->id);
		if($product) {
            $product->id = $request->id;
			$product->productName = $request->productName;
            $file = $request->file('image');
            $product->image = $file->openFile()->fread($file->getSize());
            $product->minimumQty = $request->minimumQty;
            $product->productQuantity = $request->productQuantity;
            $product->productPrice = $request->productPrice;
            $product->meassurement = $request->meassurement;
            $product->createdBy = $request->createdBy;

			if($product->save()) {
				return response()->json([
					"message" => "Product updated",
					"data" => $product
				], 200);
			}
		}
        return response()->json([
            "message" => "Product not updated",
            "data" => []
        ], 400);
	}
	
	/**
     * @OA\Delete(
     *     path="/api/v1/products/delete/{id}/{ownerId}",
     *     tags={"product"},
     *     summary="Deletes a product",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product id to delete",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
     *     ),
	 * 	   @OA\Parameter(
     *         name="ownerId",
     *         in="path",
     *         description="Owner who delted the product",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64"
     *         ),
	 * 	   ),
     *     @OA\Response(
     *         response=400,
     *         description="Product not deleted it's because either the deletion failed or product to be deleted not found",
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     },
     * )
     */
	public function delete($id, $ownerId) {
		$product = Product::find($id);
		
		if($product->delete()) {
			$product->deletedBy = $ownerId;
			$product->save();
			return response()->json([
				"message" => "Product deleted",
				"data" => []
			], 200);
		} else {
			return response()->json([
				"message" => "Product not deleted",
				"data" => []
			], 400);
		}
	}


	/**
    * @OA\Get(
	*     path="/api/v1/products/restore/{id}",
	*	  tags={"products"},
	*     description="Restore the delted product",
	*	  security={
    *     	{"bearerAuth": {}},
	*     },
	*	@OA\Parameter(
    *         name="id",
    *         in="path",
    *         description="Id of product",
    *         required=true,
    *         @OA\Schema(
    *             type="integer",
    *             format="int64"
    *         )
    *     ),
    *     @OA\Response(response="default", description="Restore the deleted product")
    * ),
    */
	public function restore($id) {
		$product = Product::onlyTrashed()->where('id', $id);
		
		if($product) {
			$product->restore();
			$product = Product::find($id);
			$product->deletedBy = NULL;
			$product->save();

			return response()->json([
				"message" => "Product restored",
				"data" => $product
			], 200);
		} else {
			return response()->json([
				"message" => "Product not restored",
				"data" => []
			], 400);
		}
	}
}
?>