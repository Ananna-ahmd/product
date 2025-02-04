<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {

        $query = Product::query();

        // category 

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // filtering 

        if ($request->filled('availability')) {
            $query->where('availability', $request->availability);
        }


        // search

        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('price', 'like', '%' . $request->search . '%');
        }

        //sorting

        if ($request->filled('sort_by') && $request->filled('sort_order')) {
            $query->orderBy($request->sort_by, $request->sort_order);
        } else {
            $query->orderBy('name', 'asc'); // Default sorting
        }

        // paginate
        $products = $query->with('category')->paginate(5);
        $categories = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'products' => view('products.table', compact('products'))->render(),
                'pagination' => (string) $products->links('pagination::bootstrap-4'),
            ]);
            //  return view('products.table', compact('products'))->render();
        }

        return view('products.index', compact('products', 'categories'));
    }

    public function store(StoreProductRequest $request)
    {


        // $product = new Product();
        // $product->name = $request->name;
        // $product->price = $request->price;
        // $product->description = $request->description;
        // $product->save();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time() . rand() . '_' . $file->getClientOriginalName();
            $imageName = md5($imageName) . '.' .  $file->getClientOriginalExtension();
            $file->storeAs('public/products', $imageName);

            // if ($product->image && file_exists(storage_path('app/public/products/' . $product->image))) {
            //     unlink(storage_path('app/public/products/' . $product->image));
            // }
            // $product->update(['image' => $imageName]);
        }
        $prepard = request()->except('image','product_id');
        $prepard['image'] = $imageName;
       

        $product = Product::create($prepard);


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

        $imageName = $product->image;


    if ($request->hasFile('image')) {
       
        if ($product->image && file_exists(storage_path('app/public/products/' . $product->image))) {
            unlink(storage_path('app/public/products/' . $product->image));
        }

        $file = $request->file('image');
        $imageName = time() . rand() . '_' . $file->getClientOriginalName();
        $imageName = md5($imageName) . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/products', $imageName);
    }
        $prepared = $request->except('image', 'product_id');
        $prepared['image'] = $imageName;

    $product->update($prepared);

       // $product->update($request->validated());

       return response()->json(['message' => 'Product updated successfully', 'product' => $product]);
    }

    public function destroy($id)
    {
        Product::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
