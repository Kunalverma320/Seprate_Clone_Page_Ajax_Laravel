<?php

namespace App\Http\Controllers;

use App\Imports\CategoryImport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CategoryController extends Controller
{
    public function index()
    {
        return view('pages.category_add');
    }
    public function storeCategory(Request $request)
    {

        $request->validate(rules: [
            'categoryname' => 'required|string|max:255',
            'categoryimage' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'categorystatus' => 'required|in:101,102',
        ]);



        if ($request->hasFile('categoryimage')) {
            $imageName = time() . '.' . $request->categoryimage->extension();
            $request->categoryimage->move(public_path('categoryimage'), $imageName);
        }

        Category::create([
            'name' => $request->categoryname,
            'image' => $imageName,
            'status' => $request->categorystatus,
        ]);

        // Return success response
        return response()->json(['success' => 'Category saved successfully!']);
    }

    public function fatchCategory()
    {
        $category = Category::all();
        return response()->json(['data' => $category]);
    }
    public function editCategory(Request $request)
    {
        $category = Category::find($request->id);
        return response()->json($category);
    }
    public function updateCategory(Request $request)
    {
        $category = Category::find($request->id);
        $oldImage = $category->image;

        $category->name = $request->category;
        $category->status = $request->status;

        if ($request->hasFile('image')) {

            if ($oldImage && file_exists(public_path('categoryimage/' . $oldImage))) {
                unlink(public_path('categoryimage/' . $oldImage));
            }
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('categoryimage'), $imageName);
            $category->image = $imageName;
        }

        $category->save();
        return response()->json(['statusCode' => 200, 'message' => 'Category Updated Successfully']);
    }
    public function deleteCategory(Request $request)
    {
        $category = Category::find($request->id);
        if ($category) {
            $imagePath = public_path('categoryimage/' . $category->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            $category->delete();
            return response()->json(['statusCode' => 200]);
        }
        return response()->json(['statusCode' => 404, 'message' => 'Category not found']);
    }
    public function ExcelCategory(Request $request)
    {
        try {
            $request->validate([
                'ExcelCategory' => 'required|file|mimes:xlsx,csv|max:2048',
            ]);

            $response = ['statusCode' => 200, 'message' => 'Files uploaded successfully'];

            if ($request->hasFile('ExcelCategory')) {
                $excelFile = $request->file('ExcelCategory');
                Excel::import(new CategoryImport, $excelFile);
                $response['message'] = 'Excel file imported successfully.';
                return response()->json(['data' => $response, 'statusCode' => 201]);
            }

            return response()->json($response);
        } catch (\Exception $ex) {
            Log::error('Error in ExcelCategory: ' . $ex->getMessage());
            return response()->json(['data' => 'Some error has occurred.', 'statusCode' => 400]);
        }
    }
}
