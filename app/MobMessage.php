<?php

namespace App;

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
  
}
