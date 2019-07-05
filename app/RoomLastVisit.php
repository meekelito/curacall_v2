<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomLastVisit extends Model
{
	protected $table = 'room_last_visit';
  protected $fillable = ['user_id', 'room_id'];
}
