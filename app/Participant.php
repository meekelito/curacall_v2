<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
  protected $fillable = ['room_id','user_id','is_read'];

  public function user()
	{ 
	  return $this->belongsTo(User::class);
	}
	
}

