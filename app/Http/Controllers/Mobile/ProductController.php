<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductForm;
use App\Http\Requests\ProductUpdateForm;
use App\Http\Resources\Product;
use App\Http\Resources\SwapResource;
use App\Models\Product as ModelsProduct;
use App\Models\Swap;
use App\Repositories\ProductRepository;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Contains route related configuration.
     *
     * @var array
     */
    protected $_config;

    /**
     * Create a new controller instance.
     *
     * @param  \App\Repositories\ProductRepository  $productRepository
     * @return void
     */
    public function __construct(protected ProductRepository $productRepository)
    {
        $this->middleware('auth:api', ['except' => ['index', 'getProduct']]);
        Auth::setDefaultDriver('api');
    }

    public function index()
    {
        $products = $this->productRepository
            ->getProducts();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getProduct($id)
    {
        $product = $this->productRepository->findOrFail($id);
        return response()->json([
            'data' => new Product($product)
        ]);
    }

    public function myProducts()
    {
        $products = $this->productRepository
            ->getAll(true);
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getMyProduct($id)
    {
        $product = $this->productRepository->findOrFail($id);
        return response()->json([
            'data' => new Product($product)
        ]);
    }

    public function create(ProductForm $request)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->create($request->all());
        } catch (\Exception $e) {
            /* rolling back first */
            DB::rollBack();

            /* storing log for errors */
            Log::error($e);

            DB::commit();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
        } finally {
            /* commit in each case */
            DB::commit();
        }
        return response()->json([
            'success' => true,
            'message' => 'Product was successfully created.',
            'product_id' => $product->id
        ]);
    }

    public function update(ProductUpdateForm $request, $id)
    {
        DB::beginTransaction();

        try {
            $product = $this->productRepository->update($request->all(), $id);
        } catch (\Exception $e) {
            /* rolling back first */
            DB::rollBack();

            /* storing log for errors */
            Log::error($e);

            DB::commit();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.',
            ]);
        } finally {
            /* commit in each case */
            DB::commit();
        }
        return response()->json([
            'success' => true,
            'message' => 'Product was successfully updated.',
            'product_id' => $product->id
        ]);
    }

    public function deleteItem(Request $request)
    {
        $product_id = $request->product_id;

        $product = \App\Models\Product::where('id', $product_id)->where('user_id', auth()->user()->id)->first();

        if ($product) {
            // $product->status = 'inactive';
            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Item Deleted Successfully'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Product Not Found'
            ]);
        }
    }
}
