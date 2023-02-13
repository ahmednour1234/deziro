<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listNotification(Request $request)
    {
        $listUsers = User::all();

        // $sortColumn = $request->input('sort', 'created_at');
        // $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listNotifications = Notification::orderBy('created_at', 'desc')
            ->where(function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhereHas(
                        'user',
                        function ($query) use ($search) {
                            $query->whereRaw("concat(first_name, ' ', last_name) like '%" . $search . "%' ");
                        }
                    )
                    ->orWhere('description', 'like', '%' . $search . '%');
            })->paginate($perPage);
        $listNotifications->appends(request()->query());
        return view('admin.notification.listNotification', compact('listUsers', 'listNotifications'));
    }

    public function addNewNotification(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'user_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 400,
                'errors' => $validator->messages(),
            ]);
        } else {
            $notification = new Notification();
            $notification->title = $request->title;
            $notification->description = $request->description;
            $notification->user_id = $request->user_id;
            $notification->save();
            return response()->json([
                'status' => 200,
                'notification' => $notification,
                'message' => 'Notification Sended Successfully',
            ]);

            // if ($request->user_id == 'users') {
            //     $listUsers = User::where('is_active', 1)->where('type', '!=', 0)->get();

            //     foreach ($listUsers as $users) {
            //         $notification = new Notification();
            //         $notification->title = $request->title;
            //         $notification->description = $request->description;
            //         $notification->user_id = $users->id;
            //         $notification->save();
            //     }
            //     if ($listUsers) {
            //         return response()->json([
            //             'status' => 200,
            //             'notification' => $notification,
            //             'message' => 'Notification Sended To Users Successfully',
            //         ]);
            //     } else {
            //         return response()->json([
            //             'status' => 404,
            //             'message' => 'Not Found Any Users',
            //         ]);
            //     }
            // } else if ($request->user_id == 'stores') {
            //     $listUsers = User::where('type', 1)->where('is_active', 1)->get();

            //     foreach ($listUsers as $users) {
            //         $notification = new Notification();
            //         $notification->title = $request->title;
            //         $notification->description = $request->description;
            //         $notification->user_id = $users->id;
            //         $notification->save();
            //     }
            //     if ($listUsers) {
            //         return response()->json([
            //             'status' => 200,
            //             'notification' => $notification,
            //             'message' => 'Notification Sended To Stores Successfully',
            //         ]);
            //     } else {
            //         return response()->json([
            //             'status' => 404,
            //             'message' => 'Not Found Any Stores',
            //         ]);
            //     }
            // } else if ($request->user_id == 'whole-sales') {
            //     $listUsers = User::where('type', 2)->where('is_active', 1)->get();

            //     foreach ($listUsers as $users) {
            //         $notification = new Notification();
            //         $notification->title = $request->title;
            //         $notification->description = $request->description;
            //         $notification->user_id = $users->id;
            //         $notification->save();
            //     }
            //     if (count($listUsers)) {
            //         return response()->json([
            //             'status' => 200,
            //             'notification' => $notification,
            //             'message' => 'Notification Sended To Whole Sales Successfully',
            //         ]);
            //     } else {
            //         return response()->json([
            //             'status' => 404,
            //             'message' => 'Not Found Any Whole Sales',
            //         ]);
            //     }
            // } else if ($request->user_id == 'retails') {
            //     $listUsers = User::where('type', 3)->where('is_active', 1)->get();

            //     foreach ($listUsers as $users) {
            //         $notification = new Notification();
            //         $notification->title = $request->title;
            //         $notification->description = $request->description;
            //         $notification->user_id = $users->id;
            //         $notification->save();
            //     }
            //     if (count($listUsers)) {
            //         return response()->json([
            //             'status' => 200,
            //             'notification' => $notification,
            //             'message' => 'Notification Sended To Retails Successfully',
            //         ]);
            //     } else {
            //         return response()->json([
            //             'status' => 404,
            //             'message' => 'Not Found Any Retails',
            //         ]);
            //     }
            // } else {
            //     $notification = new Notification();
            //     $notification->title = $request->title;
            //     $notification->description = $request->description;
            //     $notification->user_id = $request->user_id;
            //     $notification->save();
            //     return response()->json([
            //         'status' => 200,
            //         'notification' => $notification,
            //         'message' => 'Notification Sended Successfully',
            //     ]);
            // }
        }
    }
}
