<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call_type extends Model
{
    protected $table = 'call_type';

    protected $fillable = [
        'name'
    ];

}
