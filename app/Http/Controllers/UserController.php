<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    public function listUser(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();

        $search = $request->search ?: null;
        $listUser = User::where('type', 2)
        ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('phone', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);
        $listUser->appends(request()->query());
        return view('admin.user.listUser',compact('listUser','sortColumn', 'sortDirection'));

    }

    public function listUserDetail(Request $request, $id)
    {
        $userDetail = User::findOrFail($id);
        $listAddress = Address::where('user_id', $id)->where('type','user')->get();
        $listCategorys = Category::all();
        if ($userDetail) {
            return view('admin.moreDetails.userDetail', compact('userDetail','listCategorys','listAddress'));
        }
    }




    public function is_active($id){

        $user = User::findOrFail($id);

        if ($user) {
            $user->is_active = 1;
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User Activate Successfully',
        ]);

    }

    public function is_inactive($id){


        $user = User::findOrFail($id);
        if ($user) {
            $user->is_active = 0;
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'User Inactivate Successfully',
        ]);

    }
    public function approve($id)
    {

        $user = User::findOrFail($id);

        if ($user) {
            $user->status = 'accept';
            $user->is_active = 1;
        }

        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'Store Accepted Successfully',
        ]);
    }

    public function reject(Request $request, $id)
    {
            $user = User::findOrFail($id);
            if ($user) {
                $user->status = 'reject';
                $user->is_active = 0;

                $user->save();

                return response()->json([
                    'status' => 200,
                    'user' => $user,
                    'message' => 'Store Rejected Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Store Not Found",
                ]);
        }
    }
}
