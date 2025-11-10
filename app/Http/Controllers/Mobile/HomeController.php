<?php

namespace app\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BrandResource;
use App\Http\Resources\Product;
use App\Models\Brand;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
        $this->middleware('auth:api', ['except' => [
            'getNewArrivals',
            'getBestSellers',
            'getSpecialOffers',
            'getFeaturedItems',
            'getBrands',
            'getProductsByBrandID',
            'getProductsByStoreID',
            'getProductsByCategoryID',
            'globalSearch',
            'getMostViewedProducts'
        ]]);
        Auth::setDefaultDriver('api');
    }

    public function getNewArrivals()
    {
        $products = $this->productRepository
            ->getNewArrivals();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getBestSellers()
    {
        $products = $this->productRepository
            ->getBestSellers();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getSpecialOffers()
    {
        $products = $this->productRepository
            ->getSpecialOffers();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getFeaturedItems()
    {
        $products = $this->productRepository
            ->getFeaturedItems();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getBrands(Request $request)
    {
        $query = Brand::query();

        // Filter by search query
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('name', 'LIKE', "%$search%");
        }

        if ($request->has('category_id')) {
            $category_ids = explode(",", $request->input('category_id'));
            $query->whereHas('categories', function ($query) use ($category_ids) {
                $query->whereIn('category_id', $category_ids);
            });
        }

        if ($request->input('pagination') && $request->input('pagination') == 0) {
            $brands = $query->get();
        } else {
            $perPage = $request->input('limit', 20);
            $brands = $query->paginate($perPage);
        }

        return response()->json([
            'success' => true,
            'brands' => ($request->has('with_image') && $request->input('with_image') == 0) ? $brands->map(function ($brand) {
                return [
                    'id' => $brand->id,
                    'name' => $brand->name
                ];
            }) : BrandResource::collection($brands)
        ]);
    }

    public function getProductsByBrandID()
    {
        $products = $this->productRepository
            ->getProductsByBrandID();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getProductsByStoreID()
    {
        $products = $this->productRepository
            ->getProductsByStoreID();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getProductsByCategoryID()
    {
        $products = $this->productRepository
            ->getProductsByCategoryID();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function globalSearch()
    {
        $products = $this->productRepository
            ->globalSearch();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }

    public function getMostViewedProducts()
    {
        $products = $this->productRepository
            ->getMostViewedProducts();
        return response()->json([
            'data' => Product::collection($products)
        ]);
    }
}
