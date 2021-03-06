<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Case_participant extends Model
{ 
	protected $table = 'case_participants';
  protected $fillable = ['case_id','user_id','ownership','is_silent','is_read','created_at','updated_at'];

 // public function scopeActive($query,$search){
 //  	if($search != "all"){
 //  		return $query->where('case_participants.user_id',$search)
 //  	}else{
 //  		return $query;
 //  	}
 //  }

  public function user()
	{ 
	  return $this->belongsTo('App\User','user_id','id');
	}

} 
