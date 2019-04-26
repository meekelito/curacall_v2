<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

class CaseDbChannel 
{

  public function send($notifiable, Notification $notification)
  {
    $data = $notification->toDatabase($notifiable);

    return $notifiable->routeNotificationFor('database')->create([
        'id' => $notification->id,

        //customize here
        'notified_by' => $data['from_id'], //<-- comes from toDatabase() Method below
        'case_id'   => $data['from_id'],
        'type' => get_class($notification),
        'data' => $data,
        'read_at' => null,
    ]);
  }

}