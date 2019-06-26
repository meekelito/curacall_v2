<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call_type extends Model
{
    protected $table = 'call_type';

    protected $fillable = [
        'name'
    ];

    public function calltype_notification()
    {
        return $this->hasOne('App\Calltype_notification','calltype_id');
    }
}
