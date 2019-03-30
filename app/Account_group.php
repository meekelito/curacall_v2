<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account_group extends Model
{
    protected $table = 'account_group';

    protected $fillable = [
        'id',
        'group_name',
        'group_info',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at',
        'deleted_at'
    ];

}
