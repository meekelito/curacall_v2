<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
     protected $casts = [
        'data' => 'array'
    ];

    public function getCreatedAtAttribute($value)
	{
		 $datetime = new Carbon($value);
	      return $datetime->diffForHumans();
	}
}
