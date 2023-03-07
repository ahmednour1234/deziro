<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Http\Resources\CategoryeResource;
use App\Models\Address;
use App\Models\Banner;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Undefined;

class MobileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except' =>
        ['getCategories','banner']]);
        auth()->setDefaultDriver('api');
    }

    public function getCategories()
    {
        $categories = Category::where('is_active', 1)->get();

        return response()->json([
            'success' => true,
            'categories' => CategoryeResource::collection($categories) ,
        ]);
    }

    public function getAddress(Request $request)
    {
        $getAddress = Address::where('user_id', auth()->user()->id)->get();
        return response()->json([
            "success" => true,
            'address' => $getAddress,
        ], 200);
    }

    public function getSingleAddress(Request $request)
    {

        $getAddress = Address::where('user_id', auth()->user()->id)
        ->where('id',$request->id)
        ->get();
            return response()->json([
                "success" => true,
                'address' => $getAddress,
            ]);
    }

    public function addAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
                $addresses = Address::where('user_id', auth()->user()->id)->where('is_default',1)->first();
                if ($addresses) {
                    $addresses->is_default = 0;
                    $addresses->save();
                }
            }

            $address->user_id = auth()->user()->id;
            $address->city = $request->city;
            $address->address_details = $request->address_details;
            $address->location = $request->location;
            $address->is_default = $request->is_default;

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
                $addresses = Address::where('user_id', auth()->user()->id)->where('is_default',1)->first();
                if ($addresses) {
                    $addresses->is_default = 0;
                    $addresses->save();
                }
            }
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
               'message' => 'Address Deleted Succefully'
            ]);
    }



    public function banner() {

        $banner = Banner::get();
        return response()->json([
            'data' => BannerResource::collection($banner)
        ]);

    }




}
