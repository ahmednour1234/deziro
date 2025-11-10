<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function listCoupon(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listCoupons = Coupon::orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('id', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%')
                    ->orWhere('code', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('discount_value', 'like', '%' . $search . '%')
                    ->orWhere('is_percentage', 'like', '%' . $search . '%')
                    ->orWhere('min_order_amount', 'like', '%' . $search . '%')
                    ->orWhere('expiry_date', 'like', '%' . $search . '%')
                    ->orWhere('usage_limit_per_coupon', 'like', '%' . $search . '%')
                    ->orWhere('usage_limit_per_user', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listCoupons->appends(request()->query());

        return view('admin.coupons.listCoupons', compact('listCoupons', 'sortColumn', 'sortDirection'));
    }

    public function addNewCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:coupons|string',
            'description' => 'required|string',
            'is_percentage' => 'required|boolean',
            'discount_value' => $request->is_percentage ? 'required|numeric|min:1|max:100' : 'required|numeric|min:1',
            'min_order_amount' => 'required|numeric|gt:0',
            'expiry_date' => 'required|date|after:now',
            'usage_limit_per_coupon' => 'required|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $coupon = new Coupon;
            $coupon->code = $request->code;
            $coupon->description = $request->description;
            $coupon->is_percentage = $request->is_percentage;
            $coupon->discount_value = $request->discount_value;
            $coupon->min_order_amount = $request->min_order_amount;
            $coupon->expiry_date = $request->expiry_date;
            $coupon->usage_limit_per_coupon = $request->usage_limit_per_coupon;
            $coupon->usage_limit_per_user = $request->usage_limit_per_user;
            $coupon->status = 'active';
            $coupon->save();

            return response()->json([
                'status' => 200,
                'coupon' => $coupon,
                'message' => 'coupon Added Successfully',
            ]);
        }
    }


    public function editCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($coupon) {
            return response()->json([
                'status' => 200,
                'coupon' => $coupon,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Coupon Not Found",
            ]);
        }
    }

    public function updateCoupon(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|unique:coupons,id,' . $id,
            'description' => 'required|string',
            'is_percentage' => 'required|boolean',
            'discount_value' => $request->is_percentage ? 'required|numeric|min:1|max:100' : 'required|numeric|min:1',
            'min_order_amount' => 'required|numeric|gt:0',
            'expiry_date' => 'required|date|after:now',
            'usage_limit_per_coupon' => 'required|integer|min:1',
            'usage_limit_per_user' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $coupon = Coupon::find($id);

            if ($coupon) {
                $coupon->code = $request->code;
                $coupon->description = $request->description;
                $coupon->is_percentage = $request->is_percentage;
                $coupon->discount_value = $request->discount_value;
                $coupon->min_order_amount = $request->min_order_amount;
                $coupon->expiry_date = $request->expiry_date;
                $coupon->usage_limit_per_coupon = $request->usage_limit_per_coupon;
                $coupon->usage_limit_per_user = $request->usage_limit_per_user;
                $coupon->save();

                return response()->json([
                    'status' => 200,
                    'coupon' => $coupon,
                    'message' => 'Coupon Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Coupon Not Found",
                ]);
            }
        }
    }





    public function is_active($id)
    {

        $coupon = Coupon::findOrFail($id);

        if ($coupon) {
            $coupon->status = 'active';
        }

        $coupon->save();

        return response()->json([
            'status' => 200,
            'message' => 'Coupon Activate Successfully',
        ]);
    }

    public function is_inactive($id)
    {
        $coupon = Coupon::findOrFail($id);
        if ($coupon) {
            $coupon->status = 'inactive';
        }

        $coupon->save();

        return response()->json([
            'status' => 200,
            'message' => 'Coupon Inactivate Successfully',
        ]);
    }
}
