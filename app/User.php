<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id','is_curacall','email','password','role_id','fname','lname','prof_suffix','title','phone_no','mobile_no','prof_img','status','created_by','updated_by',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function messages()
    {
      return $this->hasMany(Message::class);
    }

    public function scopeIsCuraCall($query){
        if( Auth::user()->is_curacall ){
            return $query;
        }else{
            return $query->where( 'users.account_id', Auth::user()->account_id );
        }
    }
}
