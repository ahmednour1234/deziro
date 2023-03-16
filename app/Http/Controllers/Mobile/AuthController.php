<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{

    public function __construct()
    {

        $this->middleware('auth:api', ['except' => ['login', 'register', 'changepassword']]);
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
                'message'    => trans('Your Account Has Benn Rejected'),
                // 'reason'=> $user->reason,
                'errors' => [],
            ], 200);
        } else if ($user->is_active == 0) {
            auth()->guard()->logout();

            return response()->json([
                'success' => false,
                'message'    => trans('Your Account Has Benn deactivate'),
                'errors' => [],
            ], 200);
        }
        return response()->json([
            'success' => true,
            'message' => 'log in succefully',
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
                'email' => 'required|unique:users',
                'password' => 'required|min:6',
                'confirm_password' => 'required|min:6',
                'phone' => 'required|unique:users',

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
                    $user->is_active = 1;
                    $user->status = 'accept';
                    $user->first_name = $request->first_name;
                    $user->last_name = $request->last_name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->phone = $request->phone;
                    $user->countryCode = $request->countryCode;
                    $user->countryISOCode = $request->countryISOCode;
                    $user->fcm_token = $request->fcm_token;
                    $user->save();
                    $token = Auth::login($user);

                    return response()->json([
                        'success' => true,
                        'message' => 'User Registered Successfully',
                        'user' => $user,
                        'token' => $token
                    ]);
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
                'phone' => 'required|unique:users',
                'email' => 'required|unique:users',
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
                    $store->is_active = 1;
                    $store->status = 'pending';
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
                    $store->categories = implode(',',  $request->categories);
                    // $store->categories = json_encode($request->category, JSON_NUMERIC_CHECK);
                    $store->certificate = $uploadFile1;
                    $store->save();
                    $token = Auth::login($store);

                    return response()->json([
                        'success' => true,
                        'message' => 'your account is sumbitted to the admin, and waiting for approval',
                        'store' => $store,
                        'token' => $token
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
                    'countryISOCode'=>'required',
                    'countryCode'=>'required',
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
                        'message' => 'User Updated Succefully',
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
                'countryISOCode'=>'required',
                'countryCode'=>'required',
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
                        'message' => 'Store Updated Succefully',
                        'user' => $user
                    ]);
                }
            }
        }
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        $data = $request->all();

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
