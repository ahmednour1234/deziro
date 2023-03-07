<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SellingProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //Selling Product
    public function listSellingProduct(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listSellingProduct = Product::with('user', 'category')
            ->orderBy($sortColumn, $sortDirection)
            ->where('type', 'sell')
            ->where('status', 'active')
            ->whereHas('user', function ($query) {
                $query->where('type', 1);
            })
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })
            ->where(function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
                    ->orWhere('user_id', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'category',
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
        $listSellingProduct->appends(request()->query());
        return view('admin.product.selling_product.listSellingProduct', compact('listSellingProduct', 'sortColumn', 'sortDirection'));
    }

    public function SellingProductDetail($id)
    {
        $productDetail = Product::findOrFail($id);
        $imageProduct = ProductImages::where('product_id', $id)->get();
        return view('admin.product.selling_product.sellingProductDetail', compact('productDetail', 'imageProduct'));
    }


    // public function addNewSellingProduct(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'category' => 'required',
    //         'category' => 'required',
    //         'store' => 'required',
    //         'name' => 'required',
    //         'condition' => 'required',
    //         'quantity' => 'required',
    //         'price' => 'required',
    //         'description' => 'required'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 400,
    //             'errors' => $validator->messages(),
    //         ]);
    //     } else {
    //         $product = new Product();
    //         $product->type = 'sell';
    //         $product->status = 'active';
    //         $product->user_id = $request->store;
    //         $product->category_id = $request->category;
    //         $product->category_id = $request->category;
    //         $product->name = $request->name;
    //         $product->condition = $request->condition;
    //         $product->quantity = $request->quantity;
    //         $product->price = $request->price;
    //         $product->description = $request->description;

    //         $product->save();


    //         return response()->json([
    //             'status' => 200,
    //             'product' => $product,
    //             'message' => 'Product Added Successfully',
    //         ]);
    //     }
    // }

    // public function editSellingProduct($id)
    // {
    //     $sellingProduct = Product::findOrFail($id);
    //     $listUser = User::findOrFail($sellingProduct->user_id);
    //     if ($sellingProduct) {
    //         return response()->json([
    //             'status' => 200,
    //             'sellingProduct' => $sellingProduct,
    //             'users' => $listUser

    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => 400,
    //             'message' => "Selling Product Not Found",
    //         ]);
    //     }
    // }

    // public function updateSellingProduct(Request $request, $id)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'category' => 'required',
    //         'category' => 'required',
    //         // 'store' => 'required',
    //         'name' => 'required',
    //         'condition' => 'required',
    //         'quantity' => 'required',
    //         'price' => 'required',
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
    //             $product->type = 'sell';
    //             $product->status = 'active';
    //             $product->user_id = $request->store;
    //             $product->category_id = $request->category;
    //             $product->category_id = $request->category;
    //             $product->name = $request->name;
    //             $product->condition = $request->condition;
    //             $product->quantity = $request->quantity;
    //             $product->price = $request->price;
    //             $product->description = $request->description;

    //             $product->save();


    //             return response()->json([
    //                 'status' => 200,
    //                 'product' => $product,
    //                 'message' => 'Selling Product Updated Successfully',
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 404,
    //                 'message' => "Selling Product Not Found",
    //             ]);
    //         }
    //     }
    // }
    // public function deleteSellingProduct($id)
    // {
    //     $product = Product::findOrFail($id);

    //     $product->delete();
    //     return response()->json([
    //         'status' => 200,
    //         'product' => $product,
    //         'message' => 'Selling Product Deleted Succesfully'
    //     ]);
    // }
}
