<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;
use Auth;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_id','is_curacall','email','password','role_id','fname','lname','prof_suffix','title','phone_no','mobile_no','prof_img','status','created_by','updated_by', 'timezone','date_activated'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = [
      'avatar',
      'full_name'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
    
    public function getAvatarAttribute()
    {
      return url('/').'/storage/uploads/users/'.$this->attributes['prof_img'];
    }
    
    public function getFullNameAttribute()
    {
      return $this->attributes['lname'].', '.$this->attributes['fname'];
    }

    public function messages()
    {
      return $this->hasMany(Message::class);
    }

    public function account()
    {
      return $this->hasOne(Account::class,'id','account_id');
    }

    public function role()
    {
      return $this->hasOne(Role::class,'id','role_id');
    }

    public function scopeIsCuraCall($query){
        if( Auth::user()->is_curacall ){
            return $query;
        }else{
            return $query->where( 'users.account_id', Auth::user()->account_id );
        }
    }
}
