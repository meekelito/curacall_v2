<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calltype_notification extends Model
{
    protected $table = 'calltype_notification';

    protected $fillable = [
        'calltype_id', 'interval_minutes','cron_settings'
    ];

    public function call_type()
	{ 
	  return $this->belongsTo('App\Call_type','calltype_id','id');
	}
}
