<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function listAdmin(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listAdmin = User::where('type', 0)
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('phone', 'like', '%' . $search . '%')
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ")
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listAdmin->appends(request()->query());


        return view('admin.admin.listAdmin', compact('listAdmin', 'sortColumn', 'sortDirection'));
    }

    public function addNewAdmin(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
            'phone' => 'required|min:8|unique:users|max:8',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            if ($request->password == $request->confirm_password) {
                $admin = new User();
                $admin->type = 0;
                $admin->is_active = 1;
                $admin->status = 'accept';
                $admin->first_name = $request->first_name;
                $admin->last_name = $request->last_name;
                $admin->email = $request->email;
                $admin->password = Hash::make($request->password);
                $admin->phone = $request->phone;

                $admin->save();

                return response()->json([
                    'status' => 200,
                    'admin' => $admin,
                    'message' => 'Admin Added Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'password # confirm password',
                ]);
            }
        }
    }


    public function editAdmin($id)
    {
        $admin = User::findOrFail($id);
        if ($admin) {
            return response()->json([
                'status' => 200,
                'admin' => $admin,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Admin Not Found",
            ]);
        }
    }

    public function updateAdmin(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users,id,' . $id,
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
            'phone' => 'required|min:8|max:8|unique:users,id,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $admin = User::find($id);

            if ($admin) {

                if ($request->password == $request->confirm_password) {
                    $admin->type = 0;
                    $admin->is_active = 1;
                    $admin->first_name = $request->first_name;
                    $admin->last_name = $request->last_name;
                    $admin->email = $request->email;
                    $admin->password = Hash::make($request->password);

                    $admin->save();

                    return response()->json([
                        'status' => 200,
                        'admin' => $admin,
                        'message' => 'Admin Added Successfully',
                    ]);
                } else {
                    return response()->json([
                        'status' => 404,
                        'message' => 'password # confirm password',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Admin Not Found",
                ]);
            }
        }
    }


    public function deleteAdmin($id)
    {
        $admin = User::findOrFail($id);

        $admin->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Admin Deleted Succesfully'
        ]);
    }
}
