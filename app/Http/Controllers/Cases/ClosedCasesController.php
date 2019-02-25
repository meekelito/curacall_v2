<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class ClosedCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::where('status',3)->orderBy('id','desc')->get();
  	$closed_count = Cases::select(DB::raw('count(*) as total'))
											  	->where('status',3)
											  	->get();						  	
    return view( 'closed-cases',[ 'cases' => $cases,'closed_count' => $closed_count[0] ] );
  }
}
