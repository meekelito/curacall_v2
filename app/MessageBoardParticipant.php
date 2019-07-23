<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageBoardParticipant extends Model
{
   	protected $table = 'message_board_participants';
 	protected $fillable = ['messageboard_id','user_id'];

 	public function user()
	{ 
	  return $this->belongsTo('App\User','user_id','id');
	}
}
