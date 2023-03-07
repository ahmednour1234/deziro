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
        $this->middleware('auth:api', ['except' => ['index', 'getProduct', 'getBidProduct', 'getswaprequest']]);
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

    public function request_swap(Request $request)
    {
        $product_id = $request->product_id;
        $swap_product_id = $request->swap_product_id;
        $found =  $this->productRepository->where('id', $product_id)->where('type', 'swap')->first();
        // return !Swap::where('product_id', $request->product_id)->where('swap_product_id', $swap_product_id)->first();
        if ($found) {
            if ($swap_product_id) {
                if (!Swap::where('product_id', $request->product_id)->where('swap_product_id', $swap_product_id)->first()) {
                    $found = $this->productRepository->find($swap_product_id);
                    if ($found) {
                        $swap = new Swap();
                        $swap->product_id =  $product_id;
                        $swap->user_id = auth()->user()->id;
                        $swap->swap_product_id = $swap_product_id;
                        $swap->request_status = 'pending';
                        $swap->save();
                        return response()->json([
                            'success' => true,
                            'message' => 'Swap Request Added Successfully'
                        ]);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Product Not found'
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Request already exist'
                    ], 200);
                }
            } else {
                DB::beginTransaction();
                try {
                    $validator = Validator::make($request->all(), [
                        'name' => 'required|string',
                        'address_id' => 'required',
                        'type' => 'required',
                        'product_type' => 'required',
                        'condition' => 'required',
                        'description' => 'required',
                        'images.*' => 'nullable|mimes:bmp,jpeg,jpg,png,webp'


                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'success' => false,
                            'errors' => $validator->messages(),
                        ], 200);
                    }

                    // $rules = [
                    //     'name'               => ['required', 'string'],
                    //     'address_id'         => ['required'],
                    //     'type'               => ['required', 'in:sell,bid,swap'],
                    //     'product_type'       => ['required', 'in:simple,configurable'],
                    //     'condition'          => ['required', 'in:New,Used,Defective,Like New'],
                    //     'images.*'           => ['nullable', 'mimes:bmp,jpeg,jpg,png,webp'],
                    // ];
                    if (request()->images) {
                        foreach (request()->images as $key => $file) {
                            if ($file instanceof UploadedFile) {
                                $validator = array_merge([
                                    'images.' . $key => ['required', 'mimes:bmp,jpeg,jpg,png,webp'],
                                ]);
                            } else {
                                $validator = array_merge([
                                    'images.' . $key => ['required', 'integer', 'exists:product_images,id'],
                                ]);
                            }
                        }
                    }
                    //  $request->validate($rules);
                    $product = $this->productRepository->create($request->all());
                    $swap = new Swap();
                    $swap->product_id = $request->product_id;
                    $swap->user_id = $product->user_id;
                    $swap->swap_product_id = $product->id;
                    $swap->request_status = 'pending';
                    $swap->save();
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
        } else {
            return response()->json([
                'success' => false,
                "message" => 'data not found '
            ], 200);
        }
    }


    public function getswaprequest(Request $request)
    {
        $product_id = $request->product_id;
        $getswaprequest = Swap::where('product_id', $product_id)
            ->where('request_status', 'pending')
            ->orderBy('id', 'desc')
            ->whereHas('swapproduct', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('product', function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('user_id', auth()->user()->id);
                });
            })
            ->paginate(20);
        return response()->json([
            'success' => true,
            'swaprequest' => SwapResource::collection($getswaprequest)
        ]);
    }


    public function approveswaprequest(Request $request)
    {
        $request_id = $request->request_id;
        $status = $request->status;

        $swaprequest = Swap::find($request_id);


        if ($swaprequest) {

            if ($status == 'reject') {
                $swaprequest->request_status = 'reject';
                $swaprequest->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Swap Request Rejected Successfully'
                ]);
            } else if ($status == 'accept') {
                // dd($swaprequest->product_id);
                $done = ModelsProduct::where('id', $swaprequest->product_id)->first();

                $requests = Swap::where('product_id', $swaprequest->product_id)->whereHas('swapproduct', function ($query) {
                    $query->where('status', 'active');
                })->get();
                if ($requests) {
                    foreach ($requests as $request) {
                        $request->request_status = 'reject';
                        $request->save();
                    }
                }

                if ($done) {
                    $done->status = 'done';
                    $done->save();
                }

                $swaprequest->request_status = 'accept';
                $swaprequest->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Swap Request Accepted Successfully'
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'request' => []
            ], 200);
        }
    }

    public function deleteItem(Request $request)
    {
        $product_id = $request->product_id;

        $product = \App\Models\Product::where('id', $product_id)->where('user_id', auth()->user()->id)->first();

        if ($product) {
            $product->status = 'inactive';
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
