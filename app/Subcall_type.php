<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subcall_type extends Model
{
    protected $table = 'subcall_type';

    protected $fillable = [
    	'call_type',
      'name'
    ];

}
