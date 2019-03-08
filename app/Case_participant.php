<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Case_participant extends Model

{
	protected $table = 'case_participats';
  protected $fillable = ['case_id','user_id','ownership','created_at','updated_at'];

} 
