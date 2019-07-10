<?php

namespace App;
use  Carbon\Carbon;
use Auth;

use Illuminate\Database\Eloquent\Model;

class MobRoom extends Model
{
  protected $table = 'rooms';

  protected $fillable = ['user_id','name','participants_no','status','last_message', 'rpush_id'];

  public function user()
	{
	  return $this->belongsTo(User::class);
	}
	
  public function messages()
  {
    return $this->hasMany(Message::class);
  }
  
	public function getCreatedAtAttribute()
  {
     return Carbon::parse($this->attributes['created_at'])->timezone(Auth::user()->timezone);
  }
  
	public function getUpdatedAtAttribute()
  {
     return Carbon::parse($this->attributes['updated_at'])->timezone(Auth::user()->timezone);
  }

  public function setCreatedAtAttribute($value)
  {
    $this->attributes['created_at'] = Carbon::parse($value)->timezone('UTC');
  }
}

