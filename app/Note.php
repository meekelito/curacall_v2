<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
  protected $fillable = ['case_id','note','created_by','created_at','updated_at'];
}
