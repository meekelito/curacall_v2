<?php
namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;
use App\Cases;
use App\Case_participant;
use DB;

class ReportsController extends Controller
{
  public function getReportAccount()
  {
    $account = Account::all();
    return view( 'components.reports.report-accounts',['account'=>$account]);
  }
  public function getReportOncall(Request $request)
  {
    $users = User::all();
    $r1 = explode("-", $request->range);
    $date=date_create($r1[0]);
    $from = date_format($date,"Y-m-d H:i:s");
    $date=date_create($r1[1]);
    $to = date_format($date,"Y-m-d H:i:s");

    if($request->account_id == 'all'){
      $active_count = Cases::where('status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->get();
      $pending_count = Cases::where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->get();
      $closed_count = Cases::where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->get();  
    }else{
      $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->get();

      $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->get();
      $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->get();  
    }
    
    return view( 'components.reports.report-oncall',['users'=>$users,'active_count'=>$active_count[0],'pending_count'=>$pending_count[0],'closed_count'=>$closed_count[0],'account_id'=>$request->account_id,'range'=>$request->range]);
  }

}
