<?php

namespace App\Http\Controllers;

use App\Models\FeaturedProducts;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listStoreProduct(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listStoreProduct = Product::where('type', 'sell')
            ->whereNull('parent_id')
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
                    ->orWhereHas(
                        'brand',
                        function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%');
                        }
                    )
                    ->orWhere('quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('special_price', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })

            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })

            ->paginate($perPage);
        $listStoreProduct->appends(request()->query());
        return view('admin.product.listStoreProduct', compact('listStoreProduct', 'sortColumn', 'sortDirection'));
    }

    public function listFeaturedProducts(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listFeaturedProducts = FeaturedProducts::orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('product_id', 'like', '%' . $request->search . '%')
                    ->orWhereHas(
                        'product',
                        function ($query) use ($request) {
                            $query->where('name', 'like', '%' . $request->search . '%')
                                ->orWhere('quantity', 'like', '%' . $request->search . '%')
                                ->orWhere('price', 'like', '%' . $request->search . '%')
                                ->orWhere('special_price', 'like', '%' . $request->search . '%')
                                ->orWhere('created_at', 'like', '%' . $request->search . '%')
                                ->orWhere('status', 'like', '%' . $request->status . '%')
                                ->orWhere('user_id', 'like', '%' . $request->search . '%')
                                ->orWhere('type', 'like', '%' . $request->search . '%')
                                ->orWhereHas(
                                    'category',
                                    function ($query) use ($request) {
                                        $query->where('name', 'like', '%' . $request->search . '%');
                                    }
                                )
                                ->orWhereHas(
                                    'brand',
                                    function ($query) use ($request) {
                                        $query->where('name', 'like', '%' . $request->search . '%');
                                    }
                                );
                        }

                    );
            })->paginate($perPage);

        $listProducts =Product::whereNull('parent_id')->where('status', 'active')->whereNotIn('id', function ($query) {
            $query->select('product_id')->from('featured_products');
        })
        ->get();

        $listFeaturedProducts->appends(request()->query());
        return view('admin.product.listFeaturedProducts', compact('listFeaturedProducts', 'listProducts', 'sortColumn', 'sortDirection'));
    }

    public function productDetail($id)
    {
        $productDetail = Product::findOrFail($id);
        $productImages = ProductImage::where('product_id', $id)->get();
        return view('admin.moreDetails.productDetail', compact('productDetail', 'productImages'));
    }


    public function is_active($id)
    {

        $product = Product::findOrFail($id);

        if ($product) {
            $product->status = 'active';
        }

        $product->save();

        return response()->json([
            'status' => 200,
            'message' => 'Product Activate Successfully',
        ]);
    }

    public function is_inactive($id)
    {


        $product = Product::findOrFail($id);
        if ($product) {
            $product->status = 'inactive';
        }

        $product->save();

        return response()->json([
            'status' => 200,
            'message' => 'Product Inactivate Successfully',
        ]);
    }

    public function addFeaturedProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'featured_product' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $featuredProduct = new FeaturedProducts();

            $featuredProduct->product_id = $request->featured_product;
            $featuredProduct->save();

            return response()->json([
                'status' => 200,
                'message' => 'Featured Product Added Successfully'
            ]);
        }
    }

    public function deleteFeaturedProduct($id)
    {
        $featuredProduct = FeaturedProducts::find($id);
        if ($featuredProduct) {
            $featuredProduct->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Featured Product Deleted Successfully',
            ]);
        }
    }
}
