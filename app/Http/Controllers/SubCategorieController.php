<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listSubCategory(Request $request) {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $listCategorys = Category::all();

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listSubCategory = Category::with('category')
        ->whereHas('category', function ($query) {
            $query->where('is_active', 1);
        })
        ->orderBy($sortColumn, $sortDirection)
        ->where(function($query) use($search) {
            return $query->where('name','like','%' . $search . '%')
                ->orWhereHas('category', function ($query) use($search) {
                    $query->where('type', 'like', '%' . $search . '%');
                })
                // ->orWhere('is_active', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%');
        })->paginate($perPage);
        $listSubCategory->appends(request()->query());
        // if($request->ajax()){
        //     return datatables()->of(Category::all())->toJson();
        // }
        return view('admin.category.listSubCategory',compact('listCategorys','listSubCategory','sortColumn', 'sortDirection'));


    }




    public function addNewSubCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category_type' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);

        } else {
            $category = new Category();
            $category->category_id = $request->category_type;
            $category->name = $request->category_name;
            $category->is_active = '1';


            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Sub Category Added Successfully',
            ]);
        }
    }


    public function editSubCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "SubCategory Not Found",
            ]);
        }
    }


    public function updateSubCategory(Request $request, $id)
    {


        $validator = Validator::make($request->all(), [
            'category_type' => 'required',
            'category_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);

        } else {
            $category = Category::findOrFail($id);
            if ($category) {
                $category->category_id = $request->category_type;
                $category->name = $request->category_name;


                $category->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'SubCategory Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "SubCategory Not Found",
                ]);
            }

        }
    }

    public function deleteSubcategory($id)
    {
        $category = Category::findOrFail($id);

        $category->delete();
        return response()->json([
            'status' => 200,
            'message' => 'SubCategory Deleted Succesfully'
        ]);
    }

    public function is_active(Request $request,$id){

        $category = Category::findOrFail($id);
        if ($category) {
            $category->is_active = 0;
        }

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'category Inactivate Successfully',
        ]);

    }

    public function is_inactive(Request $request,$id){

        $category = Category::findOrFail($id);

        if ($category) {
            $category->is_active = 1;
        }

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'category Activate Successfully',
        ]);

    }

}

