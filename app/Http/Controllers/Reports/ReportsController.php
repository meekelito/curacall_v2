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
use App\Call_type;
use App\Subcall_type;
class ReportsController extends Controller
{
  function getAverageTime($status,$from,$to,$user_id = 'all')
  {
    /** Function to get the average time base on case_history table **/
      if($user_id == 'all')
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4";
      else
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4 AND user_id = ".$user_id;

      $data = DB::select("SELECT a.*,b.created_at as date_created,TIMESTAMPDIFF(MINUTE,b.created_at,a.created_at) as time_diff FROM curacall_db_new.case_history a 
       LEFT JOIN cases b ON a.case_id = b.id 
       WHERE a.status = ?
       AND a.case_id IN(".$case_participants.")
       AND (a.created_at BETWEEN ? AND ?) 
       GROUP BY a.case_id",[$status,$from,$to]);

      $totaltime = 0;

      if($data){
        foreach($data as $row){
                $timestamp = $row->time_diff;
                $totaltime += $timestamp;
        }

        $average_time = ($totaltime/count($data));

        return $this->convertToHumanTime($average_time);
      }else
      return "0";
  }

  function convertToHumanTime($minutes,$precision = 'first') {

    $d = floor ($minutes / 1440);
    $h = floor (($minutes - $d * 1440) / 60);
    $m = floor($minutes - ($d * 1440) - ($h * 60));
    
    $days = $d > 1 ? 'days' : 'day';
    $hours = $h > 1 ? 'hours' : 'hour';
    $minutes = $m > 1 ? 'minutes' : 'minute';

    $display = array();
    if($d > 0 )
      array_push($display, "{$d} $days");
    if($h > 0 )
      array_push($display, "{$h}  $hours");
    if($m > 0)
      array_push($display, "{$m} $minutes");

    if($precision == 'first')
      return $display[0];
    else if($precision == 'last')
      return $display[count($display)-1];
    else
      return implode(' ',$display);
}

  public function getReportAccount()
  {
    $account = Account::all();
    $calltypes = Call_type::all();

    return view( 'components.reports.report-accounts',['account'=>$account,'calltypes'=>$calltypes]);
  }

  public function getSubcalltypes(Request $request)
  {
    //temporary name use isntead of id
      $calltype = Call_type::where('name',$request->call_type)->first();
      if(!$calltype)
        return json_encode([]);
      $subcalltypes = Subcall_type::where('call_type',$calltype->id)->get();
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
    $acceptedAverage = '';
    $closedAverage = '';

    if($request->account_id == 'all'){
      
      $acceptedAverage = $this->getAverageTime(2,$from,$to);
      $closedAverage = $this->getAverageTime(3,$from,$to);

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
      if( Auth::user()->role_id != 7){
        $acceptedAverage = $this->getAverageTime(2,$from,$to,$request->account_id);
        $closedAverage = $this->getAverageTime(3,$from,$to,$request->account_id);
      }
      
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
      $acceptedAverage = $this->getAverageTime(2,$from,$to);
      $closedAverage = $this->getAverageTime(3,$from,$to);

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
    
    $total_cases = $active_count[0]->total + $pending_count[0]->total + $closed_count[0]->total;

    $closed_percentage = 0;
    if($total_cases > 0)
      $closed_percentage = ($closed_count[0]->total / $total_cases) * 100;

    return view( 'components.reports.report-oncall',['users'=>$users,'active_count'=>$active_count[0],'pending_count'=>$pending_count[0],'closed_count'=>$closed_count[0],'account_id'=>$request->account_id,'range'=>$request->range,'acceptedAverage'=>$acceptedAverage,'closedAverage'=>$closedAverage,'closed_percentage'=>round($closed_percentage,2)]);
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
