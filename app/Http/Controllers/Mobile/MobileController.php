<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryeResource;
use App\Models\Address;
use App\Models\Product;
use App\Models\Banner;
use App\Models\Category;
use App\Models\RequestCategorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class MobileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
        [
            'getCategories', 'banner', 'getAllCategories',
            'viewItem'
        ]]);
        auth()->setDefaultDriver('api');
    }

    public function getCategories()
    {
        if (auth()->user()) {
            if (auth()->user()->type == 1) {

                $categorie = User::where('id', auth()->user()->id)->first();
                $idsArray = explode(',', $categorie->categories);
                // dd($idsArray);
                $categories = Category::where('is_active', 1)->whereIn('id', $idsArray)->get();

                return response()->json([
                    'success' => true,
                    'categories' => CategoryeResource::collection($categories)
                ]);
            } else {
                $categories = Category::where('is_active', 1)->get();

                return response()->json([
                    'success' => true,
                    'categories' => CategoryeResource::collection($categories),
                ]);
            }
        } else {
            $categories = Category::where('is_active', 1)->get();

            return response()->json([
                'success' => true,
                'categories' => CategoryeResource::collection($categories),
            ]);
        }
    }

    public function getAllCategories()
    {
        $categories = Category::where('is_active', 1)->get();

        return response()->json([
            'success' => true,
            'categories' => CategoryeResource::collection($categories),
        ]);
    }

    public function updateStoreCategories(Request $request)
    {

        $store = User::where('id', auth()->user()->id)->first();


        $validator = Validator::make(
            $request->all(),
            [
                'categories' => 'required'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ], 200);
        } else {

            if ($store) {
                // dd($request->categories);
                $found = RequestCategorie::where('user_id', auth()->user()->id)->first();
                if ($found == true) {
                    $found->new_categories = implode(',',  $request->categories);
                    $found->save();

                    return response()->json([
                        "success" => true,
                        'message' => 'Request Sended successfully'
                    ]);
                } else {
                    $request_categories = new RequestCategorie();
                    $request_categories->user_id = auth()->user()->id;
                    $request_categories->new_categories = implode(',',  $request->categories);
                    $request_categories->save();

                    return response()->json([
                        "success" => true,
                        'message' => 'Request Sended successfully'
                    ]);
                }
            }
        }
    }



    public function getAddress(Request $request)
    {
        $getAddress = Address::where('type', Address::ADDRESS_TYPE_USER)
            ->where('user_id', auth()->user()->id)->get();
        return response()->json([
            "success" => true,
            'address' => $getAddress,
        ], 200);
    }

    public function getSingleAddress(Request $request)
    {

        $getAddress = Address::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->get();
        return response()->json([
            "success" => true,
            'address' => $getAddress,
        ]);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=> 'required|string|min:3',
            'city' => 'required|string|min:3',
            'address_details' => 'required|min:3|string',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ], 200);
        } else {

            $address = new Address();
            if ($request->is_default == 1) {
                $addresses = Address::where('user_id', auth()->user()->id)->where('is_default', 1)->first();
                if ($addresses) {
                    $addresses->is_default = 0;
                    $addresses->save();
                }
            }

            $address->user_id = auth()->user()->id;
            $address->name = $request->name;
            $address->city = $request->city;
            $address->address_details = $request->address_details;
            $address->location = $request->location;
            $address->is_default = $request->is_default;
            $address->type = Address::ADDRESS_TYPE_USER;
            $address->save();

            return response()->json([
                "success" => true,
                'address' => $address,
            ], 200);
        }
    }


    public function updateAddress(Request $request)
    {
        $address = Address::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        if ($request->is_default == 1) {
            $addresses = Address::where('user_id', auth()->user()->id)->where('is_default', 1)->first();
            if ($addresses) {
                $addresses->is_default = 0;
                $addresses->save();
            }
        }
        $address->name = $request->name;
        $address->city = $request->city;
        $address->address_details = $request->address_details;
        $address->location = $request->location;
        $address->is_default = $request->is_default;
        $address->save();

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }

    public function deleteAddress(Request $request)
    {
        $address = Address::where('id', $request->id)->where('user_id', auth()->user()->id)->first();

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Address Deleted Successully'
        ]);
    }



    public function banner()
    {

        $banner = Banner::get();
        return response()->json([
            'data' => BannerResource::collection($banner)
        ]);
    }

    public function viewItem(Request $request)
    {
        $product_id = $request->product_id;

        $product = Product::where('id', $product_id)->first();

        if ($product) {
            $product->views += 1;
            $product->save();

            return response()->json([
                "success" => true,
                // 'views' => $product->views
                'message' => 'Views Incremented Successfully'
            ]);
        } else {
            return response()->json([
                "success" => false,
                'message' => 'Product Not Found'
            ]);
        }
    }
}
