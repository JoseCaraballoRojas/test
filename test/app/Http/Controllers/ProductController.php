<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::All();
        
        return response()->json([
            'status' => true,
            'data' => $products->toArray(),
            'msg' => 'The products list loaded successfully',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'taste' => 'required',
            'letter' => 'required',
            'value' => 'required'
        ]);

        $product = Product::create($request->all());

        return response()->json([
            'message' => 'Products created successfully!',
            'product' => $product
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $product;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'taste' => 'nullable',
            'letter' => 'nullable',
            'value' => 'nullable'
        ]);
 
         $product->update($request->all());
 
         return response()->json([
             'message' => 'Product successfully updated!',
             'product' => $product
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product successfully deleted!'
        ]);
    }
}
