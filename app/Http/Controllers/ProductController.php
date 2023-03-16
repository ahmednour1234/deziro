<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listRequestProduct(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listRequestProduct = Product::with('user', 'category', 'category')
            ->where('status', 'pending')
            ->orderBy($sortColumn, $sortDirection)
            ->whereHas('user', function ($query) {
                $query->where('type', 1);
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
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('money_collection', 'like', '%' . $request->search . '%');
            })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('created_at', 'like', '%' . $request->date . '%');
            //     })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('store_name', 'like', '%' . $request->store_name . '%');
            // })
            ->paginate($perPage);
        $listRequestProduct->appends(request()->query());

        return view('admin.product.request_product.listRequestProduct', compact('listRequestProduct', 'sortColumn', 'sortDirection'));
    }

    public function listRejectedProduct(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listRejectedProduct = Product::with('user', 'category', 'category')->where('status', 'rejected')
            ->orderBy($sortColumn, $sortDirection)
            ->whereHas('user', function ($query) {
                $query->where('type', 1);
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
        $listRejectedProduct->appends(request()->query());
        return view('admin.product.rejected_product.listRejectedProduct', compact('listRejectedProduct', 'sortColumn', 'sortDirection'));
    }


    public function reject_product(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $product = Product::findOrFail($id);
            if ($product) {
                $product->reason = $request->reason;
                $product->status = 'rejected';

                $product->save();
                return response()->json([
                    'status' => 200,
                    'product' => $product,
                    'message' => 'Product Rejected Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Product Not Found",
                ]);
            }
        }
    }

    public function approve_product(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        if ($product) {
            $product->status = 'active';
            $product->save();
            return response()->json([
                'status' => 200,
                'product' => $product,
                'message' => 'Product Activated Successfully',
            ]);
        }
    }
}
