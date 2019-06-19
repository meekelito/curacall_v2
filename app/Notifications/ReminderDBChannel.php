<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Carbon\Carbon;

class ReminderDbChannel 
{

  public function send($notifiable, Notification $notification)
  {
    $data = $notification->toDatabase($notifiable);

    return $notifiable->routeNotificationFor('database')->updateOrCreate([
        'case_id'   => $data['case_id'],
        'type' => 'App\Notifications\ReminderNotification', //<-- comes from toDatabase() Method below
    ],[
        'id' => $notification->id,
        'type' => get_class($notification),
        'data' => $data,
        'read_at' => null,
        'created_at'  => Carbon::now()
    ]);
  }

}