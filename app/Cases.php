<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;

class Cases extends Model

{
	protected $table = 'cases';
  protected $fillable = ['case_id','account_id','call_type','subcall_type','case_message','status'];

  public function participants()
	{
	  return $this->hasMany('App\Case_participant','case_id','id')->orderBy('ownership');
	}

	public function participants_info()
	{
	  return $this->hasMany('App\Case_participant','case_id','id');
	}

} 
