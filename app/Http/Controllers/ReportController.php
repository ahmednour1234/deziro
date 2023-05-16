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


    //user report
    public function listuserreport(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;
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
                    ->orWhere('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%');
            })


            ->when($request->user_type, function ($query) use ($request) {
                return $query->where('type', $request->user_type);
            })

            ->when($request->status, function ($query) use ($request) {
                return $query->where('status',  $request->status);
            })
            ->paginate($perPage);
        $listUser->appends(request()->query());

        return view('admin.report.listuserreport', compact('listUser', 'sortColumn', 'sortDirection'));
    }


    //product report

    public function listproductreport(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;
        $productType = $request->product_type;
        $perPage = $request->limit ?: default_limit();
        $listCategory = Category::all();

        $query = Product::orderBy($sortColumn, $sortDirection)
            ->where('type', 'sell')
            ->whereNull('parent_id')
            ->where(function ($query) use ($request) {
                // Search query conditions
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('special_price', 'like', '%' . $request->search . '%')
                    ->orWhere('views', 'like', '%' . $request->search . '%')
                    ->orWhere('product_type', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by status
                $query->where('status', 'like', '%' . $request->status . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by category
                $query->whereHas('category', function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->category_name . '%');
                });
            });

        if ($productType === 'featuredproducts') {
            $query->whereHas('featuredProducts');
        } else if ($productType === 'products') {
            $query->whereDoesntHave('featuredProducts');
        }

        $listProduct = $query->paginate($perPage);

        $listProduct->appends(request()->query());

        return view('admin.report.listproductreport', compact('listProduct', 'sortColumn', 'sortDirection', 'listCategory'));
    }


    public function listorderreport(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $status = $request->status;
        $search = $request->search;

        $perPage = $request->limit ?: default_limit();

        $listOrder = Order::orderBy($sortColumn, $sortDirection)

            ->when($request->search, function ($query) use ($request) {
                return $query->where('id', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by status
                $query->where('status', 'like', '%' . $request->status . '%');
            })
            ->paginate($perPage);
        $listOrder->appends(request()->query());

        return view('admin.report.listorderreport', compact('listOrder', 'sortColumn', 'sortDirection'));
    }


    public function listtop10users(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        // $perPage = $request->limit ?: default_limit();

        $query = User::where('type', '!=', 0);

        if ($request->criteria === 'most_ordered') {
            $query->withCount('orders')->orderByDesc('orders_count');
        } elseif ($request->criteria === 'highest_order_sum') {
            $query->withSum('orders', 'grand_total')->orderByDesc('orders_sum_grand_total');
        } elseif ($request->criteria === 'most_uploading_product') {
            $query->withCount('product')->orderByDesc('product_count');
        }
        // elseif ($request->criteria === 'most_getting_orders') {
        //     $query->join('products', 'users.id', '=', 'products.user_id')
        //     ->join('order_items', 'products.id', '=', 'order_items.product_id')
        //     ->select('users.*', DB::raw('COUNT(order_items.id) as order_items_count'))
        //     ->groupBy('users.id')
        //     ->orderByDesc('order_items_count');

        // }

        $listtop10users = $query
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
            ->take(10)
            ->get();
        // $listtop10users->appends(request()->query());


        return view('admin.report.listtop10users', compact('listtop10users', 'sortColumn', 'sortDirection'));
    }

    public function listbestseller(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;
        $productType = $request->product_type;
        $perPage = $request->limit ?: default_limit();
        $listCategory = Category::all();

        $perPage = $request->limit ?: default_limit();

        $listProduct = Product::withCount('orderItems')
            ->withSum('orderItems', 'qty_ordered')
            ->orderByDesc('order_items_count')
            ->orderByDesc('order_items_sum_qty_ordered')
            ->orderBy($sortColumn, $sortDirection)
            ->where('type', 'sell')
            ->whereNull('parent_id')
            ->where(function ($query) use ($request) {
                // Search query conditions
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('special_price', 'like', '%' . $request->search . '%')
                    ->orWhere('views', 'like', '%' . $request->search . '%')
                    ->orWhere('product_type', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by status
                $query->where('status', 'like', '%' . $request->status . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by category
                $query->whereHas('category', function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->category_name . '%');
                });
            })

    ->paginate($perPage);
        $listProduct->appends(request()->query());

        return view('admin.report.listbestseller', compact('listProduct', 'sortColumn', 'sortDirection', 'listCategory'));
    }


    public function liststockinout(Request $request)
    {
        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $type = $request->type;
        $status = $request->status;
        $search = $request->search;
        $productType = $request->product_type;
        $perPage = $request->limit ?: default_limit();
        $listCategory = Category::all();

        $perPage = $request->limit ?: default_limit();

        $listProduct = Product::withCount('orderItems')
            ->withSum('orderItems', 'qty_ordered')
            ->orderByDesc('order_items_count')
            ->orderByDesc('order_items_sum_qty_ordered')
            ->orderBy($sortColumn, $sortDirection)
            ->where('type', 'sell')
            ->whereNull('parent_id')
            ->where(function ($query) use ($request) {
                // Search query conditions
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('quantity', 'like', '%' . $request->search . '%')
                    ->orWhere('price', 'like', '%' . $request->search . '%')
                    ->orWhere('special_price', 'like', '%' . $request->search . '%')
                    ->orWhere('views', 'like', '%' . $request->search . '%')
                    ->orWhere('product_type', 'like', '%' . $request->search . '%')
                    ->orWhere('type', 'like', '%' . $request->search . '%')
                    ->orWhere('created_at', 'like', '%' . $request->search . '%')
                    ->orWhere('status', 'like', '%' . $request->search . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by status
                $query->where('status', 'like', '%' . $request->status . '%');
            })
            ->where(function ($query) use ($request) {
                // Filter by category
                $query->whereHas('category', function ($query) use ($request) {
                    $query->where('id', 'like', '%' . $request->category_name . '%');
                });
            })

    ->paginate($perPage);
        $listProduct->appends(request()->query());

        return view('admin.report.liststockinout', compact('listProduct', 'sortColumn', 'sortDirection', 'listCategory'));
    }


    // $mostOrderedProduct = Product::withSum('orderItems', 'quantity')
    // ->orderByDesc('order_items_sum_quantity')
    // ->first();


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
