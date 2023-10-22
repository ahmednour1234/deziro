<?php

namespace App\Http\Controllers;

use App\Models\GiftPayment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;

class GiftPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function listGifPayments(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listGiftPayments = GiftPayment::orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('created_at', 'like', '%' . $search . '%')
                    ->orwhere('id', 'like', '%' . $search . '%')
                    ->orwhere('amount', 'like', '%' . $search . '%')
                    ->orwhere('status', 'like', '%' . $search . '%')
                    ->orwhere('payment_method', 'like', '%' . $search . '%');
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->paginate($perPage);
        $listGiftPayments->appends(request()->query());


        return view('admin.giftpayment.listGiftPayment', compact('listGiftPayments', 'sortColumn', 'sortDirection'));
    }

    public function accept($id)
    {


        $gift_payment = GiftPayment::findOrFail($id);

        if ($gift_payment) {
            $gift_payment->status = 'accept';
        }
        $user = User::where('id', $gift_payment->receiver->id)->first();
        $user->balance += $gift_payment->amount;
        $user->save();
        $gift_payment->save();
        // Event::dispatch('gift_payment.shipped.after', $gift_payment);
        return response()->json([
            'status' => 200,
            'message' => 'Gift Payment Accepted',
        ]);
    }

    public function reject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'required',

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {

            $gift_payment = GiftPayment::findOrFail($id);
            // dd($gift_payment);
            if ($gift_payment) {
                $gift_payment->reason = $request->reason;
                $gift_payment->status = 'reject';

                $gift_payment->save();
                // Event::dispatch('order.canceled.after', $order);
                return response()->json([
                    'status' => 200,
                    'message' => 'Gift Payment Rejected Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "order Not Found",
                ]);
            }
        }
    }

    public function giftPaymentDetail(Request $request, $id)
    {
        $giftpayment = GiftPayment::where('id', $id)->first();


        return view('admin.giftpayment.viewGiftPaymentDetail', compact('giftpayment'));
    }
}
