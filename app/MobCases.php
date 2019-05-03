<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Auth;
use App\MobCaseParticipant;

class MobCases extends Model

{
	protected $table = 'cases';
  protected $fillable = ['case_id','account_id','call_type','subcall_type','case_message','status'];

  
  protected $appends = [
    'link',
    'status_text',
    'status_color',
    'ownership_text',
    'ownership_color',
    'participants'
  ];
    
  public function getLinkAttribute()
  {
      return 'wwoww';
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
      'primary',
      'danger',
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
    if ($ownership === '2'){
      $text = 'Accepted';
    }
    else if ($ownership === '5'){
      $text = 'Forwarded';
    }
    return $text;
  }
  public function getOwnershipColorAttribute()
  {
    $status = $this->attributes['status'];
    $ownership = $this->attributes['ownership'];
    $text = ($status === '1') ? 'secondary' : 'success';
    if ($ownership === '2' || $ownership === '5'){
      $text = 'warning';
    }
    return $text;
  }

  public function getParticipantsAttribute()
	{
	  return MobCaseParticipant::where('case_id', $this->attributes['id'])->orderBy('ownership')->get();
	}
} 
