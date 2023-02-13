<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listPendingOrder(){
        return view('admin.order.listPendingOrder');
    }

    public function listShippingOrder(){
        return view('admin.order.listShippingOrder');
    }

    public function listCanceledOrder() {
        return view('admin.order.listCanceledOrder');
    }

    public function listDeliverydOrder() {
        return view('admin.order.listDeliverydOrder');
    }
}
