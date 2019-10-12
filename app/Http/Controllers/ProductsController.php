<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Product;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductsController extends Controller
{
    protected $user;

    /**
     * Get data of selected product
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function view($id)
    {
        $product = Product::findOrFail($id);
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Create new product
     * @param ProductRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(ProductRequest $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $product = new Product();
        $product->title = $request->title;
        $product->price = $request->price;
        $product->count = $request->count;

        if ($this->user->products()->save($product))
            return response()->json([
                'product' => $product
            ], 201);
        else
            return response()->json([
                'error' => 'Sorry, product could not be added'
            ], 500);
    }

    /**
     * Update current user selected product
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $product = Product::findOrFail($id);
        abort_if($product->user != $this->user, 404);
        $product->update($request->all());
        return response()->json([
            'product' => $product
        ]);
    }

    /**
     * Drop current user product
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete($id)
    {
        $this->user = JWTAuth::parseToken()->authenticate();
        $product = Product::findOrFail($id);
        abort_if($product->user != $this->user, 404);
        $product->delete();
        return response()->json([]);
    }
}
