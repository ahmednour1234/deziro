<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailVerification;
use Carbon\Carbon;
use App\Mail\VerificationCodeMail;
use App\Models\User;

class EmailVerificationController extends Controller
{
    public function sendCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        // ✅ Check if email already exists in users table
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => true,
                'message' => 'This email is already registered. Please login or use a different email.'
            ], 200);
        }

        $code = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        EmailVerification::updateOrCreate(
            ['email' => $request->email],
            ['code' => $code, 'expires_at' => $expiresAt]
        );

        Mail::to($request->email)->send(new VerificationCodeMail($code));

        return response()->json([
            'success' => false,
            'message' => 'Verification code sent to your email. Please check your inbox and enter the code to continue registration.'
        ], 200);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|string'
        ]);

        $record = EmailVerification::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code.'
            ], 200);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return response()->json([
                'success' => false,
                'message' => 'Verification code has expired.'
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully. You can now continue registration.',
        ], 200);
    }
}
