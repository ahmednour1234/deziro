<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ProductExport;
use App\Models\User;
use App\Models\Order;
use App\Exports\UsersExport;
use Illuminate\Support\Facades\DB;


class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    //product report

    public function listproductreport(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;

        $perPage = $request->limit ?: default_limit();
        // $search = $request->search ?: null;

        $listCategory = Category::all();

        $listProduct = Product::orderBy($sortColumn, $sortDirection)
            ->where('status', '<>', 'inactive')
            ->where('type', 'sell')
            ->whereNull('parent_id')


            ->where(function ($query) use ($request) {
                return $query->Where('name', 'like', '%' . $request->search . '%')
                    // ->orWhere('available_quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
                // ->orWhere('price', 'like', '%' . $request->search . '%')

            })



            ->where(function ($query) use ($request) {
                return $query->where('status', 'like', '%' . $request->status . '%');
            })





            ->where(function ($query) use ($request) {
                return $query->whereHas('category', function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->category_name . '%');
                });
            })


            ->paginate($perPage);


        $listProduct->appends(request()->query());




        return view('admin.report.listproductreport', compact('listProduct', 'sortColumn', 'sortDirection', 'listCategory'));
    }

    //user report
    public function listuserreport(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;
        $user_active = $request->user_active;

        $perPage = $request->limit ?: default_limit();
        // $search = $request->search ?: null;





        $listUser = User::where('type', '!=', 0)

            ->orderBy($sortColumn, $sortDirection)

            ->when($request->search, function ($query) use ($request) {
                return $query->where('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('id', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%');
            })


            ->when($request->user_type, function ($query) use ($request) {
                return $query->where('type', 'like', '%' . $request->user_type . '%');
            })

            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', 'like', '%' . $request->status . '%');
            })

            ->where(function ($query) use ($request) {
                return $query->where('is_active', 'like', '%' . $request->user_active . '%');
            })



            ->paginate($perPage);
        $listUser->appends(request()->query());

        return view('admin.report.listuserreport', compact('listUser', 'sortColumn', 'sortDirection'));
    }

    public function listorderreport(Request $request){

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $status = $request->status;
        $search = $request->search;

        $perPage = $request->limit ?: default_limit();

        $listOrder = Order::

            orderBy($sortColumn, $sortDirection)

            ->when($request->search, function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })





            ->paginate($perPage);
        $listOrder->appends(request()->query());

        return view('admin.report.listorderreport', compact('listOrder', 'sortColumn', 'sortDirection'));


    }




    //Start excel

    // public function productexport(Request $request){

    //     $sortColumn = $request->input('sort', 'created_at');
    //     $sortDirection = $request->input('direction', 'desc');
    //     $type = $request->type;
    //     $status = $request->status;
    //     $search = $request->search;

    //     $perPage = $request->limit ?: default_limit();
    //     // $search = $request->search ?: null;

    //     $listCategory = Category::all();

    //     $listProduct = Product::orderBy($sortColumn, $sortDirection)
    //         ->where('status', '<>', 'inactive')
    //         ->where('type', 'sell')
    //         ->whereNull('parent_id')


    //         ->where(function ($query) use ($request) {
    //             return $query->Where('name', 'like', '%' . $request->search . '%')
    //                 // ->orWhere('available_quantity', 'like', '%' . $request->search . '%')
    //                 ->orWhere('created_at', 'like', '%' . $request->search . '%')
    //                 ->orWhere('status', 'like', '%' . $request->search . '%');
    //             // ->orWhere('price', 'like', '%' . $request->search . '%')

    //         })



    //         ->where(function ($query) use ($request) {
    //             return $query->where('status', 'like', '%' . $request->status . '%');
    //         })





    //         ->where(function ($query) use ($request) {
    //             return $query->whereHas('category', function ($query) use ($request) {
    //                 $query->where('id', 'like', '%' . $request->category_name . '%');
    //             });
    //         })

    //         ->select('name','category_id','quantity','price','special_price','views','product_type','type','status')

    //         ->get();

    //     $export = new ProductExport($listProduct->toArray());
    //     return Excel::download($export,'products.xlsx');


    // }

    //export user

    // public function export_user(Request $request)
    // {
    //     $sortColumn = $request->input('sort', 'created_at');
    //     $sortDirection = $request->input('direction', 'desc');
    //     $type = $request->type;
    //     $status = $request->status;
    //     $search = $request->search;

    //     $perPage = $request->limit ?: default_limit();
    //     // $search = $request->search ?: null;

    //     $listUser = User::where('type', '>', 0)
    //         ->orderBy($sortColumn, $sortDirection)
    //         ->select('first_name', 'last_name', 'email', 'phone', 'created_at')

    //         ->where(function ($query) use ($request) {
    //             return $query->Where('phone', 'like', '%' . $request->search . '%')
    //                 ->orWhere('id', 'like', '%' . $request->search . '%')
    //                 ->orWhere('email', 'like', '%' . $request->search . '%')
    //                 ->orWhere('created_at', 'like', '%' . $request->search . '%')
    //                 ->orWhere('status', 'like', '%' . $request->search . '%')
    //                 ->orWhere('type', 'like', '%' . $request->search . '%');
    //         })

    //         ->where(function ($query) use ($request) {
    //             return $query->where('type', 'like', '%' . $request->type . '%');
    //         })

    //         ->where(function ($query) use ($request) {
    //             return $query->where('status', 'like', '%' . $request->status . '%');
    //         })


    //         ->get();

    //     $export = new UsersExport($listUser->toArray());
    //     return Excel::download($export, 'users.xlsx');
    // }



    //End excel

}
