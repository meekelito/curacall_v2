<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MobSupportTicket extends Model
{
	protected $table = 'support_tickets';

  protected $fillable = ['name', 'message', 'file', 'type', 'status', 'user_id'];

  protected $casts = [
    'message' => 'object',
  ];

  // protected $appends = [
  //   'user',
  // ];
    
  // public function getUserAttribute()
  // {
  //   return User::find($this->attributes['user_id']);
  // }
  
}
