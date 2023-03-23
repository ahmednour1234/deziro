<?php

namespace app\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product;
use App\Models\User;
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
            'getFeaturedItems'
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
}
