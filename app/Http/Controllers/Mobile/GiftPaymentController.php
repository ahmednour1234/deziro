<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Resources\GiftPaymentResource;
use App\Http\Resources\UserResource;
use App\Models\GiftPayment;
use App\Models\PaymentInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class GiftPaymentController extends Controller
{
    public function sendEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $email = $request->input('email');
        if (auth()->user()->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, your account is currently inactive.',
            ], 200);
        }
        if (auth()->user()->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'This action is only available for individual accounts.',
            ], 200);
        }
        if (auth()->user() && auth()->user()->email === $email) {
            return response()->json([
                'success' => false,
                'message' => 'This is your own email address.',
            ], 200);
        }
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user was found with the provided email address. Please check the email and try again.',
            ], 200);
        }

        if ($user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can send only for the individual.',
            ], 200);
        }

        if ($user->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this account is currently inactive.',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data' => new UserResource($user)
        ], 200);
    }

    public function sendAmount(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be greater than to 0.',
        ];

        // Validate the request data
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 200);
        }

        $email = $request->input('email');
        if (auth()->user()->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, your account is currently inactive.',
            ], 200);
        }
        if (auth()->user()->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'This action is only available for individual accounts.',
            ], 200);
        }
        if (auth()->user() && auth()->user()->email === $email) {
            return response()->json([
                'success' => false,
                'message' => 'This is your own email address.',
            ], 200);
        }

        $amount = $request->input('amount');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user was found with the provided email address. Please check the email and try again.',
            ], 200);
        }

        if ($user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can send only for the individual.',
            ], 200);
        }

        if ($user->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this account is currently inactive.',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Amount validate correct go to payment',
            'data' => [
                'user' => new UserResource($user),
                'amount' => $amount,
            ],
        ], 200);
    }

    public function sendGift(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:wish,omt',
        ];

        $customMessages = [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'payment_method.required' => 'The payment method field is required.',
            'payment_method.in' => 'The payment method must be either "wish" or "omt".',

        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        $email = $request->input('email');
        if (auth()->user()->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, your account is currently inactive.',
            ], 200);
        }
        if (auth()->user()->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'This action is only available for individual accounts.',
            ], 200);
        }
        if (auth()->user() && auth()->user()->email === $email) {
            return response()->json([
                'success' => false,
                'message' => 'This is your own email address.',
            ], 200);
        }
        $amount = $request->input('amount');
        $paymentMethod = $request->input('payment_method');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'No user was found with the provided email address. Please check the email and try again.',
            ], 200);
        }

        if ($user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, can send only for the individual.',
            ], 200);
        }

        if ($user->status != 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, this account is currently inactive.',
            ], 200);
        }

        if ($request->hasFile('receipt')) {
            $receipt = $request->file('receipt');
            $receiptPath = $receipt->store('receipts'); // You can adjust the storage path as needed
            // Save the $receiptPath in the 'gift_payments' table along with other details.
        }

        $gift_payment = new GiftPayment();
        $gift_payment->sender_id = auth()->user()->id;
        $gift_payment->receiver_id = $user->id;
        $gift_payment->amount = $amount;
        $gift_payment->payment_method = $paymentMethod;

        if ($request->payment_method == 'wish') {
            if ($request->ltn_number == null && $request->receipt == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Choose either an LTN number or a receipt.',
                ], 200);
            }
        } else if ($request->payment_method == 'omt') {
            if ($request->receipt == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'A receipt is required for the "omt" payment method.',
                ], 200);
            }
        }

        if ($request->ltn_number) {
            $gift_payment->ltn_number = $request->ltn_number;
        }
        if ($request->receipt) {
            $gift_payment->receipt = $receiptPath;
        }
        $gift_payment->status = 'pending';

        $gift_payment->save();

        return response()->json([
            'success' => true,
            'data' => new GiftPaymentResource($gift_payment),
            'message' => 'Payment information sended wait the approved from the admin.'
        ], 200);
    }


    public function getGiftPayments()
    {
        $user = auth()->user();

        if ($user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'This account not individual account'
            ]);
        }

        $gift_payments = GiftPayment::where(function ($query) use ($user) {
            $query->where(function ($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->orWhere(function ($query) use ($user) {
                        $query->where('receiver_id', $user->id)
                            ->where('status', 'accept');
                    });
            });
        })
            ->orderByDesc('created_at')
            ->paginate(10);


        if (!$gift_payments) {
            return response()->json([
                'success' => false,
                'data' => []
            ], 200);
        }
        return response()->json([
            'success' => true,
            'data' => GiftPaymentResource::collection($gift_payments)
        ], 200);
    }

    public function getPaymentInfo(Request $request)
    {
        $payment_info = PaymentInfo::first();
        $user = auth()->user();
        if ($user->type != 2) {
            return response()->json([
                'success' => false,
                'message' => 'This account not individual account'
            ]);
        }

        if ($payment_info) {
            $payment_method = $request->input('payment_method');

            if ($payment_method == 'wish') {
                $data = [
                    'wish_name' => $payment_info->wish_name,
                    'wish_number' => $payment_info->wish_number,
                ];
            } elseif ($payment_method == 'omt') {
                $data = [
                    'omt_name' => $payment_info->omt_name,
                    'omt_number' => $payment_info->omt_number,
                ];
            } else {
                $data = [];
            }

            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } else {

            return response()->json([
                'success' => false,
                'message' => 'Payment information not found',
            ], 200);
        }
    }
}
