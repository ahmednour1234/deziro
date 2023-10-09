<?php

namespace App\Http\Controllers;

use App\Models\AdminAction;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function listNotification(Request $request){

        $sortColumn = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');

        $perPage = $request->limit ?: default_limit();
        $search = $request->search ?: null;

        $listnotification = Notification::where('notifiable_type', 'General')
            ->orderBy($sortColumn, $sortDirection)
            ->where(function ($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('created_at', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');

        })

        ->paginate($perPage);
        $listnotification->appends(request()->query());
        return view('admin.notification.listNotification',compact('listnotification','sortColumn', 'sortDirection'));
    }

    public function addNotification(){
        $listUsers = User::where('status','active')->where('type', '!=' , 0)->get();
        return view('admin.notification.addNotification',compact('listUsers'));
    }

    public function addNewNotification(Request $request)
    {

        Event::dispatch('notification.add.after', $request);

        return redirect()->route('admin.notification.listNotification');

        //return redirect()->back();

    }

    public function editNotification($id){
        $listUsers = User::all();
     $notification = Notification::find($id);
     if($notification){
        return view('admin.notification.editNotification',compact('notification','listUsers'));
     }
    }


    public function updateNotification(Request $request,$id)
    {
        // dd($request->user_id);
        $notification=Notification::find($id);
        $notification->title=$request->title;
        $notification->description=$request->description;
        $notification->user_id=$request->user_id;

        $notification->save();

        return redirect()->route('admin.notification.listNotification');

    }
}
