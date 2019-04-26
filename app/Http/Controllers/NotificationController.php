<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;
use DB;
use App\Notifications\CaseNotification;
use App\User;

class NotificationController extends Controller
{

    // public function addnotification(Request $request)
    // {
    //     $user = User::findOrFail($request->notifiable_id);
    //     $message = str_replace("[from_name]",$request->from_name,__('notification.'.$request->type));
    //     $message = str_replace("[case_id]",$request->case_id,$message);
    //     $arr = array(
    //         'from_id'   => $request->from_id,
    //         'from_name'   => $request->from_name,
    //         'from_image' => '1551097384photo.jpg',
    //         'case_id'   => $request->case_id,
    //         'message' =>    $message,
    //         'action_url'    => route('case',[$request->case_id])
    //     );
    //     $user->notify(new CaseNotification($arr));
    // }


    public function get() {

        //$notification = Auth::user()->unreadNotifications;
        $notification = Notification::select('data','created_at',DB::raw("(CASE WHEN ISNULL(read_at) THEN 0 ELSE 1 END) as is_read"))
        					->where('notifiable_id',Auth::user()->id)
        					->where('type','App\Notifications\CaseNotification')
        					->latest()
        					->take(10)
        					->get()->toJson(JSON_PRETTY_PRINT);

        return $notification;
    }

    public function read(Request $request) {
        //Auth::user()->unreadNotifications()->find($request->id)->markAsRead();
        $result = Notification::where('notifiable_id',Auth::user()->id)
        ->where('type','App\Notifications\CaseNotification')
        ->whereNull('read_at')
        ->update(['read_at' => \Carbon\Carbon::now()]);

        return $result;
    }

    public function count()
    {
             //$notification = Auth::user()->unreadNotifications;
             $notification = Notification::where('notifiable_id',Auth::user()->id)
             ->where('type','App\Notifications\CaseNotification')
             ->whereNull('read_at')
             ->take(10)
             ->count();

            return $notification;
    }

    public function chatcount()
    {
             //$notification = Auth::user()->unreadNotifications;
             $notification = Notification::where('notifiable_id',Auth::user()->id)
             ->where('type','App\Notifications\MessageNotification')
             ->whereNull('read_at')
             ->take(10)
             ->count();

            return $notification;
    }

    /** 
     * Chat / messaging below
     */
    public function chatget() {

        //$notification = Auth::user()->unreadNotifications;
        $notification = Notification::select('data','created_at')
        					->where('notifiable_id',Auth::user()->id)
        					->where('type','App\Notifications\MessageNotification')
        					->groupBy('room_id')
                            ->orderBy('created_at','desc')
        					->get()->toJson(JSON_PRETTY_PRINT);

        return $notification;
    }

    public function chatread(Request $request) {
        //Auth::user()->unreadNotifications()->find($request->id)->markAsRead();
        $result = Notification::where('notifiable_id',Auth::user()->id)
        ->where('type','App\Notifications\MessageNotification')
        ->whereNull('read_at')
        ->update(['read_at' => \Carbon\Carbon::now()]);

        return $result;
    }



    // public function chatnotifications()
    // {
    //          //$notification = Auth::user()->unreadNotifications;
    //          $notification = Notification::select('data','created_at',DB::raw("(CASE WHEN ISNULL(read_at) THEN 0 ELSE 1 END) as is_read"))
    //          ->where('notifiable_id',Auth::user()->id)
    //          ->where('type','App\Notifications\MessageNotification')
    //          ->whereNull('read_at')
    //          ->latest()
    //          ->take(10)
    //          ->count();

    //         return $notification;
    // }
}
