<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\SubCategorie;
use App\Models\Swap;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SwapProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listSwapProduct(Request $request) {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listSwapProduct = Product::with('user','subcategorie')->where('type', 'swap')
        ->where('status', 'active')
        ->orderBy($sortColumn, $sortDirection)
        ->whereHas('user', function ($query) {
            $query->where('is_active', 1);
        })
        ->whereHas('user', function ($query) {
            $query->where('type', 1);
        })->whereHas('subcategorie', function ($query) {
            $query->where('is_active', 1);
        })
            ->where(function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('user_id', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'subcategorie',
                        function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        }
                    )
                    ->orWhere('condition', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('money_collection', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('created_at', 'like', '%' . $request->date . '%');
            //     })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('store_name', 'like', '%' . $request->store_name . '%');
            // })
            ->paginate($perPage);
        $listSwapProduct->appends(request()->query());
        return view('admin.product.swap_product.listSwapProduct',compact('listSwapProduct','sortColumn', 'sortDirection'));

    }


    public function swapProductDetail($id){
        $productDetail = Product::findOrFail($id);
        $imageProduct = ProductImages::where('product_id', $id)->get();
        return view('admin.product.swap_product.swapProductDetail',compact('productDetail','imageProduct'));
    }



    public function viewSwapProduct(Request $request,$id) {
        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $viewSwapProduct = Swap::where('product_id' , $id)
            ->where(function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('product_id', 'like', '%' . $request->search . '%')
                    ->orWhere('user_id', 'like', '%' . $request->search . '%')
                    ->orWhere('swap_product_id', 'like', '%' . $request->search . '%')
                    ->orWhere('request_status', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('created_at', 'like', '%' . $request->date . '%');
            //     })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('store_name', 'like', '%' . $request->store_name . '%');
            // })
            ->paginate($perPage);
        $viewSwapProduct->appends(request()->query());
        return view('admin.product.swap_product.viewSwapProduct',compact('viewSwapProduct'));


    }

    // public function editSwapProduct($id) {
    //     $swapProduct = Product::findOrFail($id);
    //     $listUser = User::findOrFail($swapProduct->user_id);
    //     if ($swapProduct) {
    //         return response()->json([
    //             'status' => 200,
    //             'swapProduct' => $swapProduct,
    //             'users' => $listUser
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => "Swap Product Not Found",
    //         ]);
    //     }
    // }

    // public function updateSwapProduct(Request $request, $id)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'category' => 'required',
    //         'subcategory' => 'required',
    //         // 'store' => 'required',
    //         'name' => 'required',
    //         'condition' => 'required',
    //         'description' => 'required',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 400,
    //             'errors' => $validator->messages(),
    //         ]);

    //     } else {
    //         $product = Product::findOrFail($id);
    //         if ($product) {
    //             // $product->type = 'swap';
    //             // $product->status = 'active';
    //             $product->user_id = $request->store;
    //             $product->category_id = $request->category;
    //             $product->subcategory_id = $request->subcategory;
    //             $product->name = $request->name;
    //             $product->condition = $request->condition;
    //             $product->description = $request->description;

    //             $product->save();

    //             return response()->json([
    //                 'status' => 200,
    //                 'product' => $product,
    //                 'message' => 'Swap Product Updated Successfully',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => "Swap Product Not Found",
    //             ]);
    //         }

    //     }
    // }

    // public function deleteSwapProduct($id)
    // {
    //     $product = Product::findOrFail($id);

    //     $product->delete();
    //     return response()->json([
    //         'status' => 200,
    //         'product' => $product,
    //         'message' => 'Swap Product Deleted Succesfully'
    //     ]);
    // }

}
