<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use  Carbon\Carbon;

class Cases extends Model
{

	protected $table = 'cases';
  protected $fillable = ['case_id','account_id','call_type','subcall_type','case_message','status','is_reviewed'];

  public function getCreatedAtAttribute()
  {
     return Carbon::parse($this->attributes['created_at'])->timezone(Auth::user()->timezone);
  }

  public function setCreatedAtAttribute($value)
  {
    $this->attributes['created_at'] = Carbon::parse($value)->timezone('UTC');
  }


  public function participants()
	{
	  return $this->hasMany('App\Case_participant','case_id','id')->orderBy('ownership');
	}

	public function participants_info()
	{
	  return $this->hasMany('App\Case_participant','case_id','id');
	}

	public function scopeAccount($query, $account_id)
  {
      if($account_id == "" || $account_id == "all"){
            return $query;
      }else{
           return $query->where('cases.account_id',$account_id);
      }

  }

  public function scopeCalltype($query, $call_type)
  {
      if($call_type == "" || $call_type == "all"){
            return $query;
      }else{
           return $query->where('cases.call_type',$call_type);
      }
  }

  public function scopeSubcalltype($query, $subcall_type)
  {
      if($subcall_type == "" || $subcall_type == "all"){
            return $query;
      }else{
           return $query->where('cases.subcall_type',$subcall_type);
      }
  }

  public function scopeUserrole($query, $account_id)
    {
        if($account_id == "" || $account_id == "all"){
              return $query;
        }else{
             return $query->where('cases.account_id',$account_id);
        }

    }

} 
