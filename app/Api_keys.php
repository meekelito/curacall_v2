<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class api_keys extends Model
{
    protected $table = 'api_keys';

    protected $fillable = [
        'api_key',
        'status'
    ];

}
