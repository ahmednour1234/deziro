<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategorie;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class IndividualController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listActiveIndividuals(Request $request)
    {


        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');

        $products = Product::all();
        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listActiveIndividual = User::where('type', 1)
        ->where('is_active', 1)
        ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')

                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);
        $listActiveIndividual->appends(request()->query());
        return view('admin.individual.listActiveIndividuals',compact('listActiveIndividual','products','sortColumn', 'sortDirection'));

    }

    public function listInactiveIndividuals(Request $request)
    {
        $sortColumn = $request->input('sort', 'id');
        $sortDirection = $request->input('direction', 'asc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listInactiveIndividual = User::where('type', 1)
        ->where('is_active', 0)
        ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('phone_number', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('first_name', 'like', '%' . $search . '%')
                    ->orWhere('last_name', 'like', '%' . $search . '%')
                    ->orWhere('created_at', 'like', '%' . $search . '%');
            })
            ->paginate($perPage);
        $listInactiveIndividual->appends(request()->query());
        return view('admin.individual.listInactiveIndividuals',compact('listInactiveIndividual','sortColumn', 'sortDirection'));
    }

    public function listIndividualDetail($id)
    {
        $products = Product::where('user_id', $id)->count();
        $individualDetail = User::findOrFail($id);
        $listAddress = Address::where('user_id', $id)->get();
        if ($individualDetail) {
            return view('admin.individual.individualDetail', compact('individualDetail', 'listAddress','products'));
        }
    }

    public function listIndividualProduct(Request $request, $id)
    {

        $individualDetail = User::findOrFail($id);
        $listCategorys = Category::all();
        $listSubCategorys = SubCategorie::all();
        $listStore = User::where('type', 2)->get();

        if ($individualDetail) {
            if ($request->ajax()) {
                return datatables()->of(Product::with('user', 'category', 'subcategorie')->where('user_id', $id))->toJson();
            }
            return view('admin.individual.individualProduct', compact('individualDetail', 'listCategorys', 'listSubCategorys', 'listStore'));
        }
    }


    public function is_active(Request $request, $id)
    {

        $Individual = User::findOrFail($id);
        if ($Individual) {
            $Individual->is_active = 0;
            $Individual->is_ban = 0;
            $Individual->reason = ' ';
        }

        $Individual->save();

        return response()->json([
            'status' => 200,
            'message' => 'Individual Inactivate Successfully',
        ]);

    }

    public function is_inactive(Request $request, $id)
    {

        $Individual = User::findOrFail($id);

        if ($Individual) {
            $Individual->is_active = 1;
        }

        $Individual->save();

        return response()->json([
            'status' => 200,
            'message' => 'Individual Activate Successfully',
        ]);

    }
    public function banIndividual(Request $request, $id)
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
            $individual = User::findOrFail($id);
            if ($individual) {
                $individual->reason = $request->reason;
                $individual->is_ban = 1;
                $individual->is_active = 0;

                $individual->save();
                return response()->json([
                    'status' => 200,
                    'individual' => $individual,
                    'message' => 'Individual Banned Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Individual Not Found",
                ]);
            }
        }
    }
    public function deleteIndividual($id)
    {
        $individual = User::findOrFail($id);

        $individual->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Individual Deleted Succesfully'
        ]);
    }



    public function editIndividual($id)
    {
        $individual = User::findOrFail($id);
        if ($individual) {
            return response()->json([
                'status' => 200,
                'individual' => $individual,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => "Individual Not Found",
            ]);
        }

    }



    public function updateindividual(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6',
            'mobile' => 'required|min:8',
            // 'image' => 'required',
            // 'certeficate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);

        } else {
            $individual = User::findOrFail($id);

            if ($individual) {
                // $individual->type = 2;
                // $individual->is_active = 1;
                $individual->first_name = $request->first_name;
                $individual->last_name = $request->last_name;
                $individual->full_name = $request->first_name . " " . $request->last_name;
                $individual->email = $request->email;
                $individual->password = Hash::make($request->password);
                $individual->phone_number = $request->mobile;



                if ($request->hasFile('image')) {

                $img = $request->image;
                $fileName1 = time() . '.' . $img->extension();
                $img->move(public_path('resources/assets/images/individual_image/'), $fileName1);
                $uploadFile1 = '/resources/assets/images/individual_image/' . $fileName1;
                $individual->image = $uploadFile1;
            } else {
                $size1 = '';
                $uploadFile1 = '';
            }


                $individual->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Individual Updated Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "Individual Not Found",
                ]);
            }

        }
    }


}
