<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account_role extends Model
{
    protected $table = 'account_roles';

    protected $fillable = [
        'account_id',
        'role_id',
        'msg_acaregiver',
        'msg_time',
        'msg_caregiver',
        'msg_nursing',
        'msg_coordinator',
        'msg_management',
        'msg_account_admin',
        'msg_all'
    ];

}
