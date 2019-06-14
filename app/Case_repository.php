<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Case_repository extends Model
{
    protected $table = 'case_repository';

    protected $fillable = [
        'questionnaire_id', 'client_id', 'call_information', 'caller_information', 'caregiver_information', 'patient_information', 'oncall_personnel'
    ];

}
