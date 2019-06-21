<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoomDeleteMessage extends Model
{
	protected $table = 'room_delete_messages';
  protected $fillable = ['user_id', 'room_id'];
}
