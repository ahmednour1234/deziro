<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\FeaturedProducts;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Repositories\ProductRepository;
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
            ->withoutGlobalScope('inactive')
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
        $listFeaturedProducts = Product::withoutGlobalScope('inactive')
            ->whereHas('featuredProducts')
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('name', 'like', '%' . $request->search . '%')
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
            })->paginate($perPage);

        $listProducts = Product::whereNull('parent_id')->where('status', 'active')->whereNotIn('id', function ($query) {
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

    /**
     * Show create product form.
     */
    public function create()
    {
        $stores = User::where('type', 1)->where('status', 'active')->orderBy('store_name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        return view('admin.product.create', compact('stores', 'categories', 'brands'));
    }

    /**
     * Store new product.
     */
    public function store(Request $request, ProductRepository $productRepository)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'min:2'],
            'type' => ['required', 'in:sell,bid,swap'],
            'product_type' => ['required', 'in:simple,configurable'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'brand_name' => ['nullable', 'string', 'min:2'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'price' => ['required'],
            'special_price' => ['nullable'],
            'wrap_as_gift_price' => ['nullable'],
            'description' => ['nullable', 'string'],
            'images.*' => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        // Ensure attributes key exists for repository expectations
        $data['attributes'] = $request->input('attributes', []);

        // Normalize images input to an array
        if ($request->hasFile('images')) {
            $data['images'] = $request->file('images');
        }

        $productRepository->create($data);

        return redirect()->route('admin.storeProduct.listStoreProduct')->with('success', 'Product created successfully.');
    }

    /**
     * Show edit product form.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $stores = User::where('type', 1)->where('status', 'active')->orderBy('store_name')->get();
        $categories = Category::orderBy('name')->get();
        $brands = Brand::orderBy('name')->get();
        $images = $product->images()->orderBy('sort')->get();
        return view('admin.product.edit', compact('product', 'stores', 'categories', 'brands', 'images'));
    }

    /**
     * Update product.
     */
    public function update(Request $request, $id, ProductRepository $productRepository)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'name' => ['required', 'string', 'min:2'],
            'type' => ['required', 'in:sell,bid,swap'],
            'product_type' => ['required', 'in:simple,configurable'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'brand_id' => ['nullable', 'integer', 'exists:brands,id'],
            'brand_name' => ['nullable', 'string', 'min:2'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'price' => ['required'],
            'special_price' => ['nullable'],
            'wrap_as_gift_price' => ['nullable'],
            'description' => ['nullable', 'string'],
            'keep_images.*' => ['nullable', 'integer', 'exists:product_images,id'],
            'images_upload.*' => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        $data['attributes'] = $request->input('attributes', []);

        // Build images array combining kept image IDs and newly uploaded files
        $images = [];
        if ($request->filled('keep_images')) {
            foreach ($request->input('keep_images') as $imageId) {
                $images[] = (int) $imageId;
            }
        }
        if ($request->hasFile('images_upload')) {
            foreach ($request->file('images_upload') as $file) {
                if ($file) {
                    $images[] = $file;
                }
            }
        }
        $data['images'] = $images;

        $productRepository->update($data, $id);

        return redirect()->route('admin.storeProduct.listStoreProduct')->with('success', 'Product updated successfully.');
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




    public function reject_product(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        if ($product) {
            $product->status = 'rejected';

            $product->save();
            return response()->json([
                'status' => 200,
                'product' => $product->id,
                'message' => 'Product Rejected Successfully',
            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => "Product Not Found",
            ]);
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
                'product' => $product->id,
                'message' => 'Product Activated Successfully',
            ]);
        }
    }
}
