<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImages;
use App\Models\Category;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BidProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listBidProduct(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listBidProduct = Product::with('user', 'category')
            ->where('type', 'bid')
            ->where('status', 'active')
            ->orderBy($sortColumn, $sortDirection)
            ->whereHas('user', function ($query) {
                $query->where('type', 1);
            })
            ->whereHas('user', function ($query) {
                $query->where('is_active', 1);
            })
            ->whereHas('category', function ($query) {
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
        $listBidProduct->appends(request()->query());
        return view('admin.product.bid_product.listBidProduct', compact('listBidProduct','sortColumn', 'sortDirection'));

    }

    public function BidProductDetail($id)
    {
        $bidProduct = Bid::where('product_id',$id)->get()->last();
        $countBids = Bid::where('product_id', $id)->count();
        $productDetail = Product::findOrFail($id);
        $imageProduct = ProductImages::where('product_id', $id)->get();
        return view('admin.product.bid_product.bidProductDetail', compact('productDetail', 'imageProduct','countBids','bidProduct'));
    }

    public function viewBidProduct(Request $request,$id)
    {

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $viewBidProduct = Bid::where('product_id', $id)
            // ->where('type', 'bid')
            // ->where('status', 'active')
            // ->orderBy('created_at', 'desc')
            // ->whereHas('user', function ($query) {
            //     $query->where('type', 1);
            // })
            // ->whereHas('user', function ($query) {
            //     $query->where('is_active', 1);
            // })
            // ->whereHas('category', function ($query) {
            //     $query->where('is_active', 1);
            // })
            ->where(function ($query) use ($request) {
                return $query->where('user_id', 'like', '%' . $request->search . '%')
                    ->orWhere('product_id', 'like', '%' . $request->search . '%')
                    ->orWhere('amount', 'like', '%' . $request->search . '%')
                    // ->orWhere('condition', 'like', '%' . $request->search . '%')
                    // ->orWhere('price', 'like', '%' . $request->search . '%')
                    // ->orWhere('money_collection', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('created_at', 'like', '%' . $request->date . '%');
            //     })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('store_name', 'like', '%' . $request->store_name . '%');
            // })
            ->paginate($perPage);
        $viewBidProduct->appends(request()->query());
        return view('admin.product.bid_product.viewBidProduct', compact('viewBidProduct'));







        // $viewBidProduct = Bid::where('product_id', $id)->get();
        // if ($viewBidProduct) {
        //     return view('admin.product.bid_product.viewBidProduct', compact('viewBidProduct'));
        // }
    }

    public function addNewBidProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'category' => 'required',
            'store' => 'required',
            'name' => 'required',
            'condition' => 'required',
            'day' => 'required',
            'hour' => 'required',
            'minute' => 'required',
            'bid_starting_price' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = new Product();
            $product->type = 'bid';
            $product->status = 'active';
            $product->user_id = $request->store;
            $product->category_id = $request->category;
            $product->category_id = $request->category;
            $product->name = $request->name;
            $product->condition = $request->condition;
            $product->day = $request->day;
            $product->hour = $request->hour;
            $product->minute = $request->minute;
            $product->bid_starting_price = $request->bid_starting_price;
            $product->description = $request->description;

            $product->save();

            return response()->json([
                'status' => 200,
                'product' => $product,
                'message' => 'Bid Product Added Successfully',
            ]);
        }
    }

    public function editBidProduct($id)
    {
        $bidProduct = Product::findOrFail($id);
        $listUser = User::findOrFail($bidProduct->user_id);
        if ($bidProduct) {
            return response()->json([
                'status' => 200,
                'bidProduct' => $bidProduct,
                'users' => $listUser
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Bid Product Not Found",
            ]);
        }
    }

    public function updateBidProduct(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'category' => 'required',
            'category' => 'required',
            // 'store' => 'required',
            'name' => 'required',
            'condition' => 'required',
            'day' => 'required',
            'hour' => 'required',
            'minute' => 'required',
            'bid_starting_price' => 'required',
            'description' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product =  Product::findOrFail($id);
            if ($product) {
                // $product->type = 'bid';
                // $product->status = 'active';
                $product->user_id = $request->individual_id;
                $product->category_id = $request->category;
                $product->category_id = $request->category;
                $product->name = $request->name;
                $product->condition = $request->condition;
                $product->day = $request->day;
                $product->hour = $request->hour;
                $product->minute = $request->minute;
                $product->bid_starting_price = $request->bid_starting_price;
                $product->description = $request->description;

                $product->save();


                return response()->json([
                    'status' => 200,
                    'product' => $product,
                    'message' => 'Bid Product updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Bid Product Not Found",
                ]);
            }
        }
    }







    public function deleteBidProduct($id)
    {
        $product = Product::findOrFail($id);

        $product->delete();
        return response()->json([
            'status' => 200,
            'product' => $product,
            'message' => 'Bid Product Deleted Succesfully'
        ]);
    }
}
