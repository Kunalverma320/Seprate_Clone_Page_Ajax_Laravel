<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $category=Category::where('status','101')->get();

        return view('pages.product_add',['category'=>$category]);
    }

    public function fetch()
    {
        // $products = Product::with('category')->get();
        $products = Product::leftJoin('categories', function($join) {
            $join->on('product.categoryid', '=', 'categories.id');
        })
        ->select('product.*', 'categories.id as category_id','categories.name', 'categories.image as catimage')
        ->get();


        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'productname' => 'required',
            'productprice' => 'required',
            'productsku' => 'required|unique:product,productsku',
            'description' => 'required',
            'category' => 'required',
            'image' => 'required|image'
        ]);



        $product = new Product();
        $product->productname = $request->productname;
        $product->productprice = $request->productprice;
        $product->productsku = $request->productsku;
        $product->description = $request->description;
        $product->categoryid = $request->category;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('productimages'), $imageName);
            $product->image = $imageName;
        }
        $product->save();

        return response()->json(['success' => 'Product Submited Successfully']);
    }

    public function edit(Request $request)
    {
        $product = Product::find($request->id);
        return response()->json($product);
    }

    public function update(Request $request)
{
    $request->validate([
        'productname' => 'required',
        'productprice' => 'required',
        'productsku' => 'required',
        'description' => 'required',
        'category' => 'required',
        'image' => 'nullable|image'
    ]);

    $product = Product::find($request->id);

    if ($product->image && file_exists(public_path('productimages/'.$product->image))) {
        unlink(public_path('productimages/'.$product->image));
    }

    $product->productname = $request->productname;
    $product->productprice = $request->productprice;
    $product->productsku = $request->productsku;
    $product->description = $request->description;
    $product->categoryid = $request->category;

    if ($request->hasFile('image')) {
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('productimages'), $imageName);
        $product->image = $imageName;
    }

    $product->save();

    return response()->json(['statusCode' => 200]);
}


    public function destroy(Request $request)
    {
        // $product = Product::find($request->id);
        $product = Product::find($request->id);
        if ($product) {
            $imagePath = public_path('productimages/' . $product->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $product->delete();
            return response()->json(['statusCode' => 200]);
        }
        return response()->json(['statusCode' => 404, 'message' => 'Product not found']);
    }

}
