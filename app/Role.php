<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';

    protected $fillable = [
        'role_title',
        'description',
        'msg_acaregiver',
        'msg_time',
        'msg_caregiver',
        'msg_nursing',
        'msg_management',
        'msg_account_admin',
        'msg_all'
    ];

}
