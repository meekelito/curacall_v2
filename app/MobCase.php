<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\MobCaseParticipant;

class MobCase extends Model

{
	protected $table = 'cases';
  protected $fillable = ['case_id','account_id','call_type','subcall_type','case_message','status'];

  
  protected $appends = [
    'case_code',
    'status_text',
    'status_color',
    'ownership_text',
    'ownership_color',
    'participants',
    'owner',
    'forwarded',
    'accepted',
  ];
    
  public function getCaseCodeAttribute()
  {
    return $this->attributes['case_id'];
  }
  
  public function getStatusTextAttribute()
  {
    $statusTexts = [
      'Active',
      'Pending',
      'Closed',
      'Silent',
    ];
    $stat = $this->attributes['status'];
    return $statusTexts[$stat - 1];
  }
  public function getStatusColorAttribute()
  {
    $statusColors = [
      'secondary',
      'danger',
      'success',
      'secondary',
    ];
    $stat = $this->attributes['status'];
    return $statusColors[$stat - 1];
  }
  public function getOwnershipTextAttribute()
  {
    $status = $this->attributes['status'];
    $ownership = $this->attributes['ownership'];
    $text = ($status === '1') ? 'Active' : 'Closed';
    if ($status !== '3') {
      if ($ownership === '2'){
        $text = 'Accepted';
      }
      else if ($ownership === '5'){
        $text = 'Forwarded';
      }
    }
    return $text;
  }
  public function getOwnershipColorAttribute()
  {
    $status = $this->attributes['status'];
    $ownership = $this->attributes['ownership'];
    $text = ($status === '1') ? 'secondary' : 'success';
    if ($status !== '3') {
      if ($ownership === '5'){
        $text = 'warning';
      }
      if($ownership === '2') {
        $text = 'primary';
      }
    }
    return $text;
  }

  public function getParticipantsAttribute()
	{
    return MobCaseParticipant::where('case_id', $this->attributes['id'])
    ->orderBy('ownership')->get();
	}

  public function getOwnerAttribute()
	{
    return MobCaseParticipant::where('case_id', $this->attributes['id'])
    ->where('ownership', 5)
    ->orderBy('ownership')->get();
	}

  public function getForwardedAttribute()
	{
    return MobCaseParticipant::where('case_id', $this->attributes['id'])
    ->where('ownership', 1)
    ->orderBy('ownership')->get();
	}

  public function getAcceptedAttribute()
	{
    return MobCaseParticipant::where('case_id', $this->attributes['id'])
    ->where('ownership', 2)
    ->orderBy('ownership')->get();
	}
} 
