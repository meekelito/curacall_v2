<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
  protected $fillable = ['user_id','name','participants_no','status','last_message'];

  public function user()
	{
	  return $this->belongsTo(User::class);
	}
	
  public function messages()
  {
    return $this->hasMany(Message::class);
  }
}

