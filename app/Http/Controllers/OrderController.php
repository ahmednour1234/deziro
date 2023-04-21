<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function listOrder(Request $request)
    {

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;
        $listOrder = Order::orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('created_at', 'like', '%' . $search . '%')
                    ->orwhere('id', 'like', '%' . $search . '%')
                    ->orwhere('total_item_count', 'like', '%' . $search . '%')
                    ->orwhere('status', 'like', '%' . $search . '%')
                    ->orwhere('grand_total', 'like', '%' . $search . '%');
            })
            ->when($request->status, function ($query) use ($request) {
                return $query->where('status', $request->status);
            })
            ->paginate($perPage);
        $listOrder->appends(request()->query());


        return view('admin.order.listOrder', compact('listOrder', 'sortColumn', 'sortDirection'));
    }

    public function be_shipped($id)
    {

        $order = Order::findOrFail($id);

        if ($order) {
            $order->status = Order::STATUS_SHIPPED;
        }
        $order->save();
        return response()->json([
            'status' => 200,
            'message' => 'Order Shipped Successfully',
        ]);
    }
    public function delivered($id)
    {

        $order = Order::findOrFail($id);

        if ($order) {
            $order->status = Order::STATUS_DELIVERED;
        }
        $order->save();
        return response()->json([
            'status' => 200,
            'message' => 'Order Delivered Successfully',
        ]);
    }
    public function canceled(Request $request, $id)
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
            $order = Order::findOrFail($id);
            if ($order) {
                $order->reason = $request->reason;
                $order->status = Order::STATUS_CANCELED;

                $order->save();
                return response()->json([
                    'status' => 200,
                    'order' => $order,
                    'message' => 'Order Canceled Successfully',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => "order Not Found",
                ]);
            }
        }
    }

    public function orderDetail(Request $request, $id)
    {
        $order = Order::where('id', $id)->first();


        return view('admin.order.viewOrderDetail', compact('order'));
    }
}
