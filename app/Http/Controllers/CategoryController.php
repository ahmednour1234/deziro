<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\RequestCategorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function listCategory(Request $request)
    {


        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listCategorie = Category::orderBy('created_at', 'desc')
            ->where(function ($query) use ($search) {
                return $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listCategorie->appends(request()->query());
        return view('admin.category.listCategory', compact('listCategorie', 'sortColumn', 'sortDirection'));
    }

    public function addNewCategory(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'image' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = new Category();

            $category->name = $request->name;


            // $category = Category::latest()->take(1)->first();
            if ($request->hasFile('image')) {

                $img = $request->image;
                $uploadFile1 = $img->store('category_images') ;
            } else {
                $size1 = '';
                $uploadFile1 = '';
            }

            $category->image = $uploadFile1;

            $category->save();


            return response()->json([
                'status' => 200,
                'category' => $category,
                'message' => 'Category Added Successfully',
            ]);
        }
    }

    public function is_active(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        if ($category) {
            $category->is_active = 0;
        }

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Category Inactivate Successfully',
        ]);
    }

    public function is_inactive(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        if ($category) {
            $category->is_active = 1;
        }

        $category->save();

        return response()->json([
            'status' => 200,
            'message' => 'Category Activate Successfully',
        ]);
    }


    public function editCategory(Request $request,$id){

        $category = Category::find($id);

        if($category){

            return response()->json([
                'category' => $category
            ]);

        }

    }


//     public function updateCategory(Request $request,$id){


//         $category = Category::find($id);

//         if($category){

//         $validator = Validator::make($request->all(), [
//             'name' => 'required',


//         ]);

//         if ($validator->fails()) {
//             return response()->json([
//                 'status' => 400,
//                 'errors' => $validator->messages(),
//             ]);
//         } else {

// dd($category);
//             $category->name = $request->name;

//             if ($request->hasFile('image')) {

//                 $img = $request->image;
//                 $uploadFile1 = $img->store('category_images') ;
//             } else {
//                 $size1 = '';
//                 $uploadFile1 = '';
//             }

//             $category->image = $uploadFile1;

//             $category->save();


//             return response()->json([
//                 'status' => 200,
//                 'category' => $category,
//                 'message' => 'Category Updated Successfully',
//             ]);
//         }
//     }

//     }


    public function updateCategory(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $category = Category::find($id);


            if ($category) {


                if ($request->hasFile('image')) {

                    $img = $request->image;
                    $uploadFile1 = $img->store('category_images') ;
                } else {
                    $size1 = '';
                    $uploadFile1 = $category->image;
                }
     


                    $category->image = $uploadFile1;
                    $category->name = $request->name;
                    $category->save();

                    return response()->json([
                        'status' => 200,
                        'category' => $category,
                        'message' => 'category Added Successfully',
                    ]);
                }


        }
    }



    public function listrequesttochangecategories(Request $request){
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listCategorys = Category::all();

        // $listNewCategory = RequestCategorie::

        $listRequestCategorie = RequestCategorie::orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('created_at', 'like', '%' . $search . '%')
                 ->orWhereHas('user', function ($query) use($search) {
                        $query->where('store_name', 'like', '%' . $search . '%');
                    });
            })->paginate($perPage);
        $listRequestCategorie->appends(request()->query());
        // if($request->ajax()){
        //     return datatables()->of(Category::all())->toJson();
        // }
        return view('admin.category.listrequesttochangecategories', compact('listRequestCategorie','listCategorys', 'sortColumn', 'sortDirection'));
    }

    public function rejectRequest(Request $request,$id) {

        $reject = RequestCategorie::find($id);

        $reject->delete();

        return response()->json([
            'message' => 'Request Rejected '
        ]);

    }

    public function approveRequest(Request $request,$id) {

        $requestCategorie = RequestCategorie::where('id',$id)->first();


        $user = User::where('id',$requestCategorie->user_id)->first();
        if($user){
            $user->categories = $requestCategorie->new_categories;
            $user->save();
            $requestCategorie->delete();

            return response()->json([
                'message' => 'Request Approved '
            ]);

        }

    }
}
