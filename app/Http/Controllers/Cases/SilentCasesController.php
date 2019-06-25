<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Cases;
use DB;
use Cache;
use Auth;

class SilentCasesController extends Controller
{
  public function index()
  {
    $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',Auth::user()->id)
              ->where('b.is_silent',1) 
              ->where(function($q) {
                  $q->where('cases.status',1)
                  ->orWhere('cases.status',2);
              })
              ->orderBy('cases.id','DESC')
              ->select('cases.id','cases.case_id','cases.created_by','cases.status','cases.created_at')
              ->get();

    $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                  ->where('b.user_id',Auth::user()->id)
                  ->where('b.is_silent',1)
                  ->where(function($q) {
                    $q->where('cases.status',1)
                    ->orWhere('cases.status',2);
                  })
                  ->select(DB::raw('count(cases.id) as total'))
                  ->get();    
                            
    return view( 'silent-cases',[ 'cases' => $cases,'active_count' => $active_count[0] ] );
  }
}
