<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $table = 'accounts';

    protected $fillable = [
        'account_name',
        'address_main',
        'address_secondary',
        'city',
        'state',
        'zipcode',
        'phone_main',
        'phone_secondary',
        'account_info',
        'email',
        'created_by',
        'updated_by'
    ];

}
