<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Utilities\Request;
use Session;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{

    public function listHome()
    {
        if (Auth::check()) {
            $users = User::count();
            $products = Product::whereNull('parent_id')->count();
            $orders = Order::where('status', 'delivered')->count();


            $mostuploadingstores = User::store()->withCount('product')
                ->orderBy('product_count', 'desc')
                ->having('product_count', '>', 0)
                ->take(5)
                ->get();


            $mostorderingusers = User::retail()->withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->having('orders_count', '>', 0)
                ->take(5)
                ->get();

            $mostgetingorderstores =  DB::table('orders')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->join('products', 'order_items.product_id', '=', 'products.id')
                ->join('users', 'products.user_id', '=', 'users.id')
                ->where('users.type', 1)
                ->select('users.*', DB::raw('count(order_items.id) as orders_count'))
                ->groupBy('users.id')
                ->orderByDesc('orders_count')
                ->limit(5)
                ->get();

            return view('admin.home.listHome', compact('users', 'products', 'orders', 'mostuploadingstores', 'mostorderingusers', 'mostgetingorderstores'));
        }
        return redirect('/login');
    }
}
