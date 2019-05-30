<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MobCaseParticipant extends Model
{
	protected $table = 'case_participants';
  protected $fillable = ['case_id','user_id','ownership','created_at','updated_at'];

  protected $appends = [
    'user',
  ];
    
  public function getUserAttribute()
  {
      return User::find($this->attributes['user_id']);
  }

} 
