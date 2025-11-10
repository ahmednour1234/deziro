<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Mail\Message;


class AuthController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login', 'register', 'resetPassword', 'checkcode', 'newPassword']]);
        auth()->setDefaultDriver('api');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        // $credentials = $request->only('email', 'password', 'is_active');

        $token = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 200);
        }

        $user = Auth::user();
        if (request()->get('fcm_token')) {
            $user->update(['fcm_token' => $request->fcm_token]);
        }

        if ($user->status == 'pending') {
            auth()->guard()->logout();

            return response()->json([
                'success' => false,
                'message'    => trans('your account is not activated yet'),
                'errors' => [],
            ], 200);
        } else if ($user->status == 'reject') {
            auth()->guard()->logout();

            return response()->json([
                'success' => false,
                'message'    => trans('Your Account has been Rejected'),
                // 'reason'=> $user->reason,
                'errors' => [],
            ], 200);
        } else if ($user->sttaus == 'inactive') {
            auth()->guard()->logout();

            return response()->json([
                'success' => false,
                'message'    => trans('Your Account has been deactivate'),
                'errors' => [],
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully',
            'user' => $user,
            'token' => $token,
        ]);
    }


    public function register(Request $request)
    {
        if ($request->type == '2') {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|min:2',
                'last_name' => 'required|min:2',
                'email' => 'required' . ($request->status != 'active' && $request->status != 'pending' ? '' : '|unique:users') . '|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:password',
                'phone' => 'required' . ($request->status != 'active' && $request->status != 'pending' ? '' : '|unique:users'),
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->messages(),
                ], 200);
            } else {
                if ($request->password == $request->confirm_password) {
                    $user = new User();
                    $user->type = 2;
                    $user->status = 'verification';
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->phone = $request->phone;
                    $user->countryCode = $request->countryCode;
                    $user->countryISOCode = $request->countryISOCode;
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                    // $token = Auth::login($user);

                    // Generate a random code
                    $code = strtoupper(Str::random(4) . rand(1000, 9999));

                    // Save the code to the user's record (you might need a 'password_reset_code' column in the users table)
                    $user->code = $code;
                    $user->save();



                    Mail::to($user->email)->send(new PasswordResetMail($code));



                    return response()->json([
                        'success' => true,
                        'message' => 'A verification code has been sent to your email address. Please check your email and enter the OTP code to complete the registration process.',
                        'user' => $user,
                        // 'token' => $token
                    ], 200);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'password # confirm password',
                    ], 200);
                }
            }
        } else if ($request->type == '1') {
            $validator = Validator::make($request->all(), [
                'store_name' => 'required|string|min:3',
                'first_name' => 'required|min:2|string',
                'last_name' => 'required|min:2|string',
                'phone' => 'required' . ($request->status != 'active' && $request->status != 'pending' ? '' : '|unique:users'),
                'email' => 'required' . ($request->status != 'active' && $request->status != 'pending' ? '' : '|unique:users') . '|email',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6',
                'categories' => 'required',
                'certificate' => 'mimes:png,jpg,jpeg,pdf|max:2048',
                'fcm_token' => 'required|min:3',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->messages(),
                ], 200);
            } else {
                if ($request->password == $request->confirm_password) {
                    if ($request->hasFile('certificate')) {

                        $certificate = $request->certificate;
                        $fileName1 = time() . '.' . $certificate->extension();
                        $certificate->move(public_path('/resources/assets/images/store_certificate/'), $fileName1);
                        $uploadFile1 = '/resources/assets/images/store_certificate/' . $fileName1;
                    } else {
                        $size1 = '';
                        $uploadFile1 = '';
                    }
                    $store = new User();
                    $store->type = 1;
                    $store->status = 'verification';
                    $store->first_name = $request->first_name;
                    $store->last_name = $request->last_name;
                    $store->phone = $request->phone;
                    $store->countryCode = $request->countryCode;
                    $store->countryISOCode = $request->countryISOCode;
                    $store->email = $request->email;
                    $store->password = Hash::make($request->password);
                    $store->store_name = $request->store_name;
                    $store->position = $request->position;
                    $store->tax_number = $request->tax_number;
                    // dd($request->categories);
                    $store->categories = $request->categories;
                    // $store->categories = json_encode($request->category, JSON_NUMERIC_CHECK);
                    $store->certificate = $uploadFile1;
                    $store->save();
                    // $token = Auth::login($store);

                    // Generate a random code
                    $code = strtoupper(Str::random(4) . rand(1000, 9999));

                    // Save the code to the user's record (you might need a 'password_reset_code' column in the users table)
                    $store->code = $code;
                    $store->save();



                    Mail::to($store->email)->send(new PasswordResetMail($code));



                    return response()->json([
                        'success' => true,
                        'message' => 'A verification code has been sent to your email address. Please check your email and enter the OTP code to complete the registration process.',
                        'store' => $store,
                        // 'token' => $token
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'password # confirm password',
                    ], 200);
                }
            }
        }
    }

    public function logout()
    {
        $user = Auth::user();
        $user->fcm_token = null;
        $user->save();

        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Successfully logged out',
        ]);
    }


    public function profile()
    {
        $user = Auth::user();

        return response()->json([
            'success' => true,
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {

        $user = Auth::user();
        if ($user->type == 2) {
            $validator = Validator::make(
                $request->all(),
                [
                    'first_name' => 'required|min:2|string',
                    'last_name' => 'required|min:2|string',
                    'email' => 'required|email|unique:users,id,' . $user->id,
                    'phone' => 'required|min:8|max:8|unique:users,id,' . $user->id,
                    'countryISOCode' => 'required',
                    'countryCode' => 'required',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->messages(),
                ]);
            } else {
                $id = Auth::user()->id;
                $user = User::find($id);
                if ($user) {
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->phone = $request->phone;
                    $user->countryCode = $request->countryCode;
                    $user->countryISOCode = $request->countryISOCode;
                    $user->fcm_token = $request->fcm_token;
                    $user->save();


                    return response()->json([
                        'success' => true,
                        'message' => 'User Updated Successully',
                        'user' => $user
                    ]);
                }
            }
        } elseif ($user->type == 1) {
            $validator = Validator::make($request->all(), [
                'store_name' => 'required|string|min:3',
                'first_name' => 'required|min:2|string',
                'last_name' => 'required|min:2|string',
                'email' => 'required|email|unique:users,id,' . $user->id,
                'phone' => 'required|unique:users,id,' . $user->id,
                'categories' => 'required',
                'countryISOCode' => 'required',
                'countryCode' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->messages(),
                ], 200);
            } else {
                $id = Auth::user()->id;
                $user = User::find($id);
                if ($user) {

                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->phone = $request->phone;
                    $user->countryCode = $request->countryCode;
                    $user->countryISOCode = $request->countryISOCode;
                    $user->email = $request->email;
                    $user->store_name = $request->store_name;
                    $user->categories = implode(',',  $request->categories);


                    // $user->categories = json_encode($request->category, JSON_NUMERIC_CHECK);

                    $user->save();

                    return response()->json([
                        'success' => true,
                        'message' => 'Store Updated Successully',
                        'user' => $user
                    ]);
                }
            }
        }
    }



    public function resetPassword(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with the given email does not exist.'
            ]);
        } else {
            // Generate a random code
            $code = strtoupper(Str::random(4) . rand(1000, 9999));

            // Save the code to the user's record (you might need a 'password_reset_code' column in the users table)
            $user->code = $code;
            $user->save();

            // Send the code to the user's email
            try {
                Mail::to($user->email)->send(new PasswordResetMail($code));
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while sending the email.'
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Password reset code has been sent to your email.'
            ]);
        }
    }
    public function verificationCode(Request $request)
    {

        $email = $request->email;
        $enteredCode = $request->code;

        $user = User::where('email', $email)->where('code', $enteredCode)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid verification code or email address. Please double-check your information and try again.',
            ]);
        } else {
            if ($user->type == 2) {
                User::where('email', $email)
                ->where('code', '!=', $enteredCode)
                ->delete();
                $user->status = 'active';
                $token = Auth::login($user);
                $user->code = Null;
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Registration completed successfully. Your account is now active.',
                    'user' => $user,
                    'token' => $token
                ], 200);
            }
            if ($user->type == 1) {
                User::where('email', $email)
                ->where('code', '!=', $enteredCode)
                ->delete();
                $user->status = 'pending';
                $user->code = Null;
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Registration completed successfully. Your account has been sent to the administrator for approval. Please wait for the approval process to be completed.',
                    'user' => $user,
                ], 200);
            }
        }


    }


    public function checkcode(Request $request)
    {

        $email = $request->email;
        $enteredCode = $request->code;

        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User with the given email does not exist.'
            ]);
        }

        if ($user->code !== $enteredCode) {
            return response()->json([
                'success' => false,
                'message' => 'Entered code does not match the stored code.'
            ]);
        }



        return response()->json([
            'success' => true,
            'message' => 'Code matches.'
        ]);
    }

    public function newPassword(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|max:255|exists:users',
                'code' => 'required',
                'password' => 'required|min:6|string',
            ]
        );

        $user  = User::where([['email', '=', $request->email], ['code', '=', $request->code]])->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or code.',
            ], 200);
        }

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ], 200);
        } else {

            if ($user->code != $request->code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid code.',
                ], 200);
            }

            $user->code = null;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Password has been changed successfully.',
            ], 200);
        }
    }


    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

        $rest = $request->reset;

        $validator = Validator::make(
            $request->all(),
            [
                'oldpassword' => 'required',
                'password' => 'required|confirmed|min:6|string',
                'password_confirmation' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->messages(),
            ], 200);
        } else {

            if (Hash::check($data['oldpassword'], $user->password)) {
                $isPasswordChanged = true;

                $user->password = bcrypt($data['password']);
                $user->save();

                return response()->json([
                    'success' => true,
                    'message' => 'Password has been changed successfully.',
                ], 200);
            } else {

                return response()->json([
                    'success' => false,
                    'message' => 'The old Password does not match.',
                ], 200);
            }
        }
    }
}
