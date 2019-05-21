<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\User;

class MobNote extends Model

{
	protected $table = 'notes';
  protected $fillable = ['note','case_id','created_by'];

  
  protected $appends = [
    'user',
  ];
    
  public function getUserAttribute()
  {
    $created_by = $this->attributes['created_by'];

    return User::find($created_by);
  }
} 
