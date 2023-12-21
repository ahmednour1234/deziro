<?php

namespace App\Http\Controllers;
use App\Models\PaymentInfo;


class PaymentInfoController extends Controller
{
    public function editPaymentInfo(){
        $payment_info = PaymentInfo::first();

        return view('admin.payment_info.editPaymentInfo',compact('payment_info'));

    }

    public function updatePaymentInfo(){
        $payment_info = PaymentInfo::first();

        $payment_info->wish_number = request()->wish_number;
        $payment_info->wish_name = request()->wish_name;
        $payment_info->omt_number = request()->omt_number;
        $payment_info->omt_name = request()->omt_name;
        $payment_info->call_number = request()->call_number;
        $payment_info->wats_number = request()->wats_number;

        $payment_info->save();
        return redirect()->back()->with('success', 'info updated successfully');
    }
}
