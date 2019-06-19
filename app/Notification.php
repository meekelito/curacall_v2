<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Notifications\CaseNotification;
use App\Notifications\MessageNotification;
use Auth;

class Notification extends Model
{
     protected $casts = [
     	'id'  => 'char',
        'data' => 'array'
    ];

    public function getCreatedAtAttribute($value)
	{
		 $datetime = new Carbon($value);
	      return $datetime->diffForHumans();
	}

	public static function notify_user($param = array(),$user,$type = "notification")
	{
		  $arr = array(
		  	  'from_id'     => Auth::user()->id
	      );

		  $arr = array_merge($arr,$param);

		  if($type == "chat")
		  	$user->notify(new MessageNotification($arr));
		  else
		  	$user->notify(new CaseNotification($arr));


	}
}
