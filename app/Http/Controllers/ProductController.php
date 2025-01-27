<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        
        $query = Product::latest();

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('price', 'like', '%' . $request->search . '%');
        }
    
        $products = $query->paginate(5); 
    
        if ($request->ajax()) {
            return response()->json([
                'products' => view('products.table', compact('products'))->render(),
                'pagination' => (string) $products->links(),
            ]);
          //  return view('products.table', compact('products'))->render();
        }
    
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

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

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
