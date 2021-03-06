<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notification;
use Auth;
use DB;
use App\Notifications\CaseNotification;
use App\User;
use Validator;

class NotificationController extends Controller
{
    public function get(Request $request) {
        $notification = Notification::select('notifications.id',DB::raw('CONCAT("'. asset('/storage/uploads/users/').'","/",COALESCE(b.prof_img, "default.png")) as prof_img'),'notifications.data','notifications.created_at',DB::raw("(CASE WHEN ISNULL(notifications.read_at) THEN 0 ELSE 1 END) as is_read"))
                            ->leftJoin('users as b','notifications.notified_by','=','b.id')
        					->where('notifications.notifiable_id',Auth::user()->id)
                              ->when(request('type'), function ($query) {
                                  if(request('type') == "case")
                                        return $query->where('notifications.type','App\Notifications\CaseNotification');
                                  elseif(request('type') == 'chat')
                                        return $query->where('notifications.type','App\Notifications\MessageNotification')->groupBy('notifications.room_id');
                                  elseif(request('type') == 'reminder')
                                      return $query->where('type','App\Notifications\ReminderNotification')->groupBy('notifications.case_id');
                              })
        					->orderByRaw('is_read asc, created_at desc')
        					->take(10)
        					->get()->toJson(JSON_PRETTY_PRINT);

        return $notification;
    }

    public function count()
    {
          $notification = Notification::where('notifiable_id',Auth::user()->id)
              ->when(request('type'), function ($query) {
                  if(request('type') == "case")
                        return $query->where('notifications.type','App\Notifications\CaseNotification');
                  elseif(request('type') == 'chat')
                        return $query->where('notifications.type','App\Notifications\MessageNotification');
                  elseif(request('type') == 'reminder')
                      return $query->where('type','App\Notifications\ReminderNotification');
              })
             ->whereNull('read_at')
             ->count();

            return $notification;
    }

    // public function count()
    // {
    //          //$notification = Auth::user()->unreadNotifications;
    //          $notification = Notification::where('notifiable_id',Auth::user()->id)
    //          ->where('type','App\Notifications\CaseNotification')
    //          ->whereNull('read_at')
    //          ->take(10)
    //          ->count();

    //         return $notification;
    // }

    // public function chatcount()
    // {
    //          //$notification = Auth::user()->unreadNotifications;
    //          $notification = Notification::where('notifiable_id',Auth::user()->id)
    //          ->where('type','App\Notifications\MessageNotification')
    //          ->whereNull('read_at')
    //          ->take(10)
    //          ->count();

    //         return $notification;
    // }

    // public function remindercount()
    // {
    //            //$notification = Auth::user()->unreadNotifications;
    //          $notification = Notification::where('notifiable_id',Auth::user()->id)
    //          ->where('type','App\Notifications\ReminderNotification')
    //          ->whereNull('read_at')
    //          ->take(10)
    //          ->count();

    //         return $notification;
    // }

    public function read(Request $request) {

        $validator = Validator::make($request->all(),[ 
              'id' => 'required'
            ]);

        if( $validator->fails() ){
          return response()->json([ 
            "response"=>"Invalid parameters", 
            "message"=>$validator->errors()
          ],406);
        }

        $userUnreadNotification = auth()->user()
                            ->unreadNotifications()
                            ->where('id', $request->id)
                            ->first();

            if($userUnreadNotification) {
               $result =  $userUnreadNotification->markAsRead();
               return $result;
            }
    }

}
