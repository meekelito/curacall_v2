<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $table = 'company_profile';

    protected $fillable = [
        'company_name',
        'address_main',
        'address_secondary',
        'city',
        'state',
        'zipcode',
        'phone_main',
        'phone_secondary',
        'company_info',
        'email',
        'is_mobilesupport'
    ];

}
