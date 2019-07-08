<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageBoard extends Model
{
   	protected $table = 'message_board';
 	protected $fillable = ['title','content','attachments','created_by'];

 	public function user()
	{ 
	  return $this->belongsTo('App\User','created_by','id');
	}
}
