<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function listBanner(Request $request) {

        $listBanner = Banner::paginate(5);
        return view('admin.banner.listBanner',compact('listBanner'));
   }
}
