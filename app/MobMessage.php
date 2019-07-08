<?php

namespace App;
use  Carbon\Carbon;
use Auth;

use Illuminate\Database\Eloquent\Model;

class MobMessage extends Model
{
	protected $table = 'messages';

  protected $fillable = ['room_id','message', 'user_id'];

  protected $appends = [
    'user',
  ];
    
  public function getUserAttribute()
  {
    return User::find($this->attributes['user_id']);
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
