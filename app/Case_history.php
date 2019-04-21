<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Case_history extends Model
{
	protected $table = 'case_history';
  	protected $fillable = ['case_id','is_visible','status','action_note','note','created_by','created_at','updated_at'];

  	public function case()
	{
  		return $this->belongsTo(Cases::class,'case_id','case_id');
	}

} 
