<?php
namespace App\Http\Controllers\Reports;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Account;
use App\User;
use App\Cases;
use App\Case_participant;
use DB;
use Auth;
use App\CallType;
use App\SubcallType;
class ReportsController extends Controller
{
  public function getReportAccount()
  {
    $account = Account::all();
    $calltypes = CallType::all();

    return view( 'components.reports.report-accounts',['account'=>$account,'calltypes'=>$calltypes]);
  }

  public function getSubcalltypes(Request $request)
  {
    //temporary name use isntead of id
      $calltype = CallType::where('name',$request->call_type)->first();
      if(!$calltype)
        return json_encode([]);
      $subcalltypes = SubcallType::where('call_type',$calltype->id)->get();
      return json_encode($subcalltypes);
  }

  public function getReportOncall(Request $request)
  {
    
    if( Auth::user()->is_curacall ){
      $users = User::all();
    }else{
      $users = User::where('account_id',Auth::user()->account_id)->get();
    }

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

    if( Auth::user()->role_id == 7  ){
      $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->get();

      $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->get();
      $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->get();  
    }
    
    return view( 'components.reports.report-oncall',['users'=>$users,'active_count'=>$active_count[0],'pending_count'=>$pending_count[0],'closed_count'=>$closed_count[0],'account_id'=>$request->account_id,'range'=>$request->range]);
  }


  public function getReportActiveCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::whereBetween('cases.created_at', array($from, $to))
              ->where('status',1)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$request->user_id)
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('cases.status',1)
              ->orderBy('cases.id','DESC')
              ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
              ->get();
    }

    return view('components.reports.report-active-case-list',['cases' => $cases]);
  }

  public function getReportPendingCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::whereBetween('cases.created_at', array($from, $to))
              ->where('status',2)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$request->user_id)
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('cases.status',2)
              ->orderBy('cases.id','DESC')
              ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
              ->get();
    }

    return view('components.reports.report-pending-case-list',['cases' => $cases]);
  }

  public function getReportClosedCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::whereBetween('cases.created_at', array($from, $to))
              ->where('status',3)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
              ->where('b.user_id',$request->user_id)
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('cases.status',3)
              ->orderBy('cases.id','DESC')
              ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
              ->get();
    }

    return view('components.reports.report-pending-case-list',['cases' => $cases]);
  }

}
