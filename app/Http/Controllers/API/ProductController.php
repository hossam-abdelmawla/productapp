<?php

namespace App\Http\Controllers\API;

use App\Models\Product;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Product as ProductResource;
use App\Http\Controllers\API\BaseController;

class ProductController extends BaseController
{

    public function index()
    {
        $product = Product::all();
        return $this->sendResponse(ProductResource::collection($product), 'All Products Sent');
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $validator = validator::make($input, [
            'name'   => 'required',
            'detail' => 'required',
            'price'  => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please Validate Error', $validator->errors());
        }
        $product = Product::create($input);
        return $this->sendResponse(new ProductResource($product), 'Product Created Succesfully');
    }

    public function show($id)
    {
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('Product Not Found');
        }
        return $this->sendResponse(new ProductResource($product), 'Product Found Successfully');
    }

    public function update(Request $request, Product $product)
    {
        $input = $request->all();
        $validator = validator::make($input, [
            'name'   => 'required',
            'detail' => 'required',
            'price'  => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Please Validate Error', $validator->errors());
        }
        $product->name = $input['name'];
        $product->detail = $input['detail'];
        $product->price = $input['price'];
        $product->save();
        return $this->sendResponse(new ProductResource($product), 'Product Updated Successfully');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse(new ProductResource($product), 'Product Deleted Successfully');
    }
}
