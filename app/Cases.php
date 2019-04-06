<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model

{
	protected $table = 'cases';
  protected $fillable = ['case_id','account_id','call_type','subcall_type','case_message','status'];

} 
