<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CallType extends Model
{
    protected $table = 'call_type';

    protected $fillable = [
        'name'
    ];

}
