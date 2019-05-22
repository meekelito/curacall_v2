<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use  Carbon\Carbon;
use Auth;

class Case_history extends Model
{
	protected $table = 'case_history';
  	protected $fillable = ['case_id','is_visible','status','action_note','note','created_by','created_at','updated_at'];

  	public function case()
	{
  		return $this->belongsTo(Cases::class,'case_id','case_id');
	}

	public function getCreatedAtAttribute()
    {
     	return Carbon::parse($this->attributes['created_at'])->timezone(Auth::user()->timezone);
    }

    public function setCreatedAtAttribute($value)
    {
    	$this->attributes['created_at'] = Carbon::parse($value)->timezone('UTC');
    }


} 
