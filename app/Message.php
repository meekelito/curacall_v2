<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  Carbon\Carbon;
use Auth;

class Message extends Model
{
  	protected $fillable = ['room_id','message'];

  	public function user()
	{
  		return $this->belongsTo(User::class);
	}

	public function getCreatedAtAttribute()
    {
     	return Carbon::parse($this->attributes['created_at'])->timezone(Auth::user()->timezone);
    }

    public function setCreatedAtAttribute($value)
    {
    	$this->attributes['created_at'] = Carbon::parse($value)->timezone('UTC');
    }
}
