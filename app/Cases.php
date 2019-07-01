<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use Carbon\Carbon;

class Cases extends Model
{

	protected $table = 'cases';
  
  protected $fillable = ['case_id', 'account_id', 'call_type', 'subcall_type', 'case_message', 'number_of_calls', 'call_language', 'call_reason', 'reason_call_subtype', 'contacted_translation_company', 'pin_number', 'full_message', 'direct_deposit_or_receive_a_check', 'lab_doctor_notification', 'hospital_related', 'medical_emergency', 'time_of_call', 'name_of_compliance_officer', 'name_of_insurance_company', 'name_of_oncall_staff', 'name_of_the_home_health_agency', 'hospital_id', 'name_of_the_lab_company', 'new_or_existing_employee', 'new_referral_or_previous_patient', 'start_end_time_of_the_shift', 'team', 'was_timesheet_submitted', 'type_of_results', 'violence', 'casescol', 'call_handled_by_initials', 'created_by', 'created_on', 'absent_or_late', 'caller_id', 'caller_first_name', 'caller_last_name', 'caller_email_address', 'caller_type', 'caller_type_details', 'caller_details', 'caller_alternative_telephone_number', 'extension_direct_line', 'anything_else', 'caller_calling_from', 'mobile_number', 'caller_telephone_extension', 'caller_telephone_number', 'caller_patient_telephone', 'telephone_number', 'caller_relationship_with_field_worker', 'employee_first_name', 'employee_last_name', 'employee_date_time_of_shift_end', 'employee_date_time_of_shift_start', 'caregiver_type', 'pin_code', 'caregiver_first_name', 'caregiver_last_name', 'provided_caregiver_first_name', 'provided_caregiver_last_name', 'caregiver_time_and_attendance_pin_code', 'how_late_will_you_be_to_your_shift', 'patient_date_and_time_of_first_visit', 'patient_first_name', 'patient_last_name', 'provided_patient_first_name', 'provided_patient_last_name', 'patient_telephone_number', 'patient_telephone_number_confirmation', 'status', 'is_reviewed'];

  public function getCreatedAtAttribute()
  {
     return Carbon::parse($this->attributes['created_at'])->timezone(Auth::user()->timezone);
  }

  public function setCreatedAtAttribute($value)
  {
    $this->attributes['created_at'] = Carbon::parse($value)->timezone('UTC');
  }


  public function participant()
  {
    return $this->hasMany('App\Case_participant','case_id','id');
    // return $this->hasMany('App\Case_participant','case_id','id')->orderBy('ownership');

  }

  public function participants()
	{
	  return $this->hasMany('App\Case_participant','case_id','id')->orderBy('ownership');
	}

	public function participants_info()
	{
	  return $this->hasMany('App\Case_participant','case_id','id');
	}

	public function scopeAccount($query, $account_id)
  {
      if($account_id == "" || $account_id == "all"){
            return $query;
      }else{
           return $query->where('cases.account_id',$account_id);
      }

  }

  public function scopeCalltype($query, $call_type)
  {
      if($call_type == "" || $call_type == "all"){
            return $query;
      }else{
           return $query->where('cases.call_type',$call_type);
      }
  }

  public function scopeSubcalltype($query, $subcall_type)
  {
      if($subcall_type == "" || $subcall_type == "all"){
            return $query;
      }else{
           return $query->where('cases.subcall_type',$subcall_type);
      }
  }

  public function scopeUserrole($query, $account_id)
    {
        if($account_id == "" || $account_id == "all"){
              return $query;
        }else{
             return $query->where('cases.account_id',$account_id);
        }

    }

} 
