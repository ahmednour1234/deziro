<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Utilities\Request;
use Session;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{

    public function listHome()
    {
        if (Auth::check()) {
            return view('admin.home.listHome');
        }
        return redirect('/login');
    }
}
