<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cases extends Model

{
	protected $table = 'cases';
  protected $fillable = ['case_id','case_message'];

} 
