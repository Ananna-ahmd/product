<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('products.index', compact('products'));
    }

    public function store(StoreProductRequest $request)
    {  
        $product = Product::create($request->validated());

        // $product = new Product();
        // $product->name = $request->name;
        // $product->price = $request->price;
        // $product->description = $request->description;
        // $product->save();

        return response()->json(['message' => 'Product created successfully', 'product' => $product]);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->validated());

        return response()->json($product);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
