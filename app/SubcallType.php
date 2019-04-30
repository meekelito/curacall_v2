<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubcallType extends Model
{
    protected $table = 'subcall_type';

    protected $fillable = [
    	'call_type',
        'name'
    ];

}
