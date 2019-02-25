<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class ActiveCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::where('status',1)->orderBy('id','desc')->get();
  	$active_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',1)
											  	->get();						  	
    return view( 'active-cases',[ 'cases' => $cases,'active_count' => $active_count[0] ] );
  }
}
