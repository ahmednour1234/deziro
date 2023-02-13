<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategorie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Utilities\Request;
use Session;
use Illuminate\Support\Facades\Redirect;


class HomeController extends Controller
{

    public function login() {

        return view('admin.auth.viewLogin');
}

public function logout(){
    // return redirect('/')->with(Auth::logout());
    // Session::flush();

    Auth::logout();

    return redirect('/login');
 }



public function userLogin(Request $request){
    request()->validate([
        'email' => 'required',
        'password' => 'required',
        ]);


        if (Auth::attempt(['email' => $request->email, 'password' => $request->password ,'type'=> 0 , 'is_active'=> 1])){
                return redirect()->intended('home');
            }
        return Redirect::to("/login")->withSuccess('Oppes! You have entered invalid credentials');

}

   public function listHome()
   {
    if (Auth::check()) {
        return view('admin.home.listHome');
    }
    return redirect('/login');

   }



}
