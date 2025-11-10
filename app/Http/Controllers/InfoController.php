<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listInfo() {
        return view('admin.info.editInfo');
        }
}
