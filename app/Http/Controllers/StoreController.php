<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\store;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class StoreController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function listRequestStore(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listRequestStore = User::where('type', 1)->where('status', 'pending')
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($request) {
                return $query->where('store_name', 'like', '%' . $request->search . '%')
                    ->orWhere('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $request->search . "%' ")
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })->paginate($perPage);
        $listRequestStore->appends(request()->query());
        return view('admin.store.listRequestStore', compact('listRequestStore', 'sortColumn', 'sortDirection'));
    }
    public function listRejectedStore(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listRejectedStore = User::where('type', 1)->where('status', 'reject')
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($request) {
                return $query->where('store_name', 'like', '%' . $request->search . '%')
                    ->orWhere('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $request->search . "%' ")
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })->paginate($perPage);
        $listRejectedStore->appends(request()->query());
        return view('admin.store.listRejectedStore', compact('listRejectedStore', 'sortColumn', 'sortDirection'));
    }

    public function listStore(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');


        $perPage = $request->limit ?: default_limit();
        // $search = $request->search ?: null;
        // $listCategorys = Category::all();
        $listStore = User::where('type', 1)->where('status', 'active')
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($request) {
                return $query->where('store_name', 'like', '%' . $request->search . '%')
                    ->orWhere('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhereRaw("concat(first_name, ' ', last_name) like '%" . $request->search . "%' ")
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%');
            })
            // ->where(function ($query) use ($request) {
            //     return $query->where('created_at', 'like', '%' . $request->date . '%');
            // })
            //     ->where(function ($query) use ($request) {
            //         return $query->where('store_name', 'like', '%' . $request->store_name . '%');
            // })
            ->paginate($perPage);
        $listStore->appends(request()->query());
        return view('admin.store.listStore', compact('listStore', 'sortColumn', 'sortDirection'));
    }


    public function createStore()
    {
        $listCategorys = Category::all();
        return view('admin.store.crud_modal.addStoreModal', compact('listCategorys'));
    }



    public function addNewStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:8|max:8|unique:users',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
            'store_name' => 'required',
            'category_type' => 'required'

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
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

                $store->status = 'active';
                $store->first_name = $request->first_name;
                $store->last_name = $request->last_name;
                $store->phone = $request->phone;
                $store->email = $request->email;
                $store->password = Hash::make($request->password);
                $store->store_name = $request->store_name;
                $store->position = $request->position;
                $store->tax_number = $request->tax_number;
                $store->categories = implode(',',  $request->category_type);
                // $store->categories = json_encode($request->category, JSON_NUMERIC_CHECK);
                $store->certificate = $uploadFile1;
                $store->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Store Added Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'password # confirm password',
                ]);
            }
        }
    }


    public function editStore($id)
    {

        $store = User::findOrFail($id);
        $categoryIds = explode(',', $store->categories);
        $allCategories =Category::where('is_active',1)->get();
        return view('admin.store.crud_modal.editStoreModal', compact('store', 'allCategories','categoryIds'));
    }



    public function updateStore(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|min:8|max:8|unique:users,id,' . $id,
            'email' => 'required|unique:users,id,' . $id,
            'password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
            'store_name' => 'required',
            'category_type' => 'required'
            // 'certificate' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $store =  User::findOrFail($id);

            if ($store) {
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
                    $store->type = 1;
                    $store->status = 'active';
                    $store->first_name = $request->first_name;
                    $store->last_name = $request->last_name;
                    $store->phone = $request->phone;
                    $store->email = $request->email;
                    $store->password = Hash::make($request->password);
                    $store->store_name = $request->store_name;
                    $store->position = $request->position;
                    $store->tax_number = $request->tax_number;
                    $store->categories = implode(',',  $request->category_type);
                    // $store->categories = json_encode($request->category, JSON_NUMERIC_CHECK);
                    $store->certificate = $uploadFile1;
                    $store->save();

                    return response()->json([
                        'status' => 200,
                        'message' => 'Store Added Successfully',
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
                    'message' => "Store Not Found",
                ]);
            }
        }
    }



}
