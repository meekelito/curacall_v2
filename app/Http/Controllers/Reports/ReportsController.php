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
  public function charttrend(Request $request)
  {
      

      if($request->account_id == "all")
      {
        // $cases = Cases::leftJoin('accounts as b','b.id','=','cases.account_id')
        //       ->select('cases.account_id','b.account_name',DB::raw('count(0) as total'),DB::raw('MONTH(cases.created_at) as month'),DB::raw("CONCAT(MONTHNAME(cases.created_at),' ',YEAR(cases.created_at)) as month_year"))
        //       ->groupBy('cases.account_id',DB::raw('EXTRACT(YEAR_MONTH FROM cases.created_at)'))
        //       ->calltype($request->call_type)
        //       ->subcalltype($request->subcall_type)
        //       ->whereYear('cases.created_at',$request->year)
        //       ->get();

         $cases = Cases::select(DB::raw("'All Accounts' as account_name"),DB::raw('count(0) as total'),DB::raw('MONTH(cases.created_at) as month'),DB::raw("CONCAT(MONTHNAME(cases.created_at),' ',YEAR(cases.created_at)) as month_year"))
              ->groupBy(DB::raw('EXTRACT(YEAR_MONTH FROM cases.created_at)'))
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->whereYear('cases.created_at',$request->year)
              ->get();

      }else if($request->call_type == "all")
      {
             $cases = Cases::leftJoin('accounts as b','b.id','=','cases.account_id')
              ->select('cases.account_id','b.account_name',DB::raw('count(0) as total'),DB::raw('MONTH(cases.created_at) as month'),DB::raw("CONCAT(MONTHNAME(cases.created_at),' ',YEAR(cases.created_at)) as month_year"))
              ->groupBy('cases.call_type',DB::raw('EXTRACT(YEAR_MONTH FROM cases.created_at)'))
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->whereYear('cases.created_at',$request->year)
              ->account($request->account_id)
              ->get();
      }else{
            $cases = Cases::leftJoin('accounts as b','b.id','=','cases.account_id')
              ->select('cases.account_id','b.account_name',DB::raw('count(0) as total'),DB::raw('MONTH(cases.created_at) as month'),DB::raw("CONCAT(MONTHNAME(cases.created_at),' ',YEAR(cases.created_at)) as month_year"))
              ->groupBy('cases.subcall_type',DB::raw('EXTRACT(YEAR_MONTH FROM cases.created_at)'))
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->whereYear('cases.created_at',$request->year)
              ->account($request->account_id)
              ->get();
      }

            if($cases)
            {
              $cases_arr = array();
           
              foreach($cases as $case)
              {
                  $cases_arr[$case->account_name][$case->month] = $case->total;
              }

              $final = array();
              $accounts = array();
              foreach($cases_arr as $key => $value)
              {
                $accounts[] = $key;
                $total_per_month = array('-','-','-','-','-','-','-','-','-','-','-','-');
                  foreach($value as $month => $total)
                  {
                       
                        for($x=0;$x<=11;$x++)
                        {
                          if($x == ($month-1)){
                            $total_per_month[$x] = $total;
                            break;
                          }
                        }
                        
                  }
                  $final[] = array(
                                  "name"  =>  $key,
                                  "type"  =>  "line",
                                  "stack" =>  "Total",
                                  "data"  =>  $total_per_month
                             );
                 
                    
              }

               return json_encode(array("accounts"=>$accounts,"data"=>$final));
            }else
             return json_encode([]);


           
    
  }

  public function chartoverall(Request $request)
  {
      $r1 = explode("-", $request->range);
      $date=date_create($r1[0]);
      $from = date_format($date,"Y-m-d H:i:s");
      $date=date_create($r1[1]);
      $to = date_format($date,"Y-m-d H:i:s");

      if($request->account_id == "all")
      {
       $cases = Cases::leftJoin('accounts as b','b.id','=','cases.account_id')->whereBetween('cases.created_at',[$from,$to])
              ->select(DB::raw('count(0) as value'),'b.account_name as name')->groupBy('cases.account_id')
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->get();

            return json_encode($cases);
      }else if($request->call_type == "all")
      {
        $cases = Cases::whereBetween('cases.created_at',[$from,$to])
              ->account($request->account_id)
              ->select('cases.call_type as name',DB::raw('count(0) as value'))->groupBy('cases.call_type')
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->get();

            return json_encode($cases);
      }else
      {
        $cases = Cases::whereBetween('cases.created_at',[$from,$to])
              ->account($request->account_id)
              ->select('cases.subcall_type as name',DB::raw('count(0) as value'))->groupBy('cases.subcall_type')
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->get();

        return json_encode($cases);
      }
  }

  function getAverageTime($status,$from,$to,$action_note = '', $user_id = 'all')
  {
    /** Function to get the average time base on case_history table **/
      $action_note_condition = '';
      if($action_note != '')
        $action_note_condition = " AND action_note = '$action_note'";

      if($user_id == 'all')
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4";
      else
        $case_participants = "SELECT case_id FROM case_participants WHERE  ownership != 4 AND user_id = ".$user_id;

      $data = DB::select("SELECT a.*,b.created_at as date_created,TIMESTAMPDIFF(MINUTE,b.created_at,a.created_at) as time_diff FROM case_history a 

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

   
    
    return view( 'components.reports.report-oncall',['users'=>$users,'account_id'=>$request->account_id,'range'=>$request->range]);
  }

  public function getOverallCaseStatus(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date=date_create($r1[0]);
    $from = date_format($date,"Y-m-d H:i:s");
    $date=date_create($r1[1]);
    $to = date_format($date,"Y-m-d H:i:s");

    if($request->account_id == 'all'){


      $active_count = Cases::where('status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->first();
      $pending_count = Cases::where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->first();
      $closed_count = Cases::where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->whereBetween('created_at', array($from, $to))
                      ->first();  
    }else{     
      $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->first();

      $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->first();
      $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',$request->account_id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->first();  
    }

    if( Auth::user()->role_id == 7  ){
      $active_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',1)
                      ->select(DB::raw('count(cases.id) as total'))
                      ->first();

      $pending_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->first();
      $closed_count = Cases::Join('case_participants AS b','cases.id','=','b.case_id')
                      ->where('b.user_id',Auth::user()->id)
                      ->whereBetween('cases.created_at', array($from, $to))
                      ->where('cases.status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->first();  
    }

    return json_encode(array('active'=>$active_count->total,'pending'=>$pending_count->total,'closed'=>$closed_count->total));
  }

 public function getOverallAverage(Request $request)
  {
      $r1 = explode("-", $request->range);
      $date=date_create($r1[0]);
      $from = date_format($date,"Y-m-d H:i:s");
      $date=date_create($r1[1]);
      $to = date_format($date,"Y-m-d H:i:s");

      $readAverage = 0;
      $acceptedAverage = 0;
      $closedAverage = 0;

    if($request->account_id == 'all'){
      $readAverage = $this->getAverageTime(1,$from,$to,'Case Read');
      $acceptedAverage = $this->getAverageTime(2,$from,$to);
      $closedAverage = $this->getAverageTime(3,$from,$to);
    }else
    {
        if( Auth::user()->role_id != 7){
          $readAverage = $this->getAverageTime(1,$from,$to,'Case Read',$request->account_id);
          $acceptedAverage = $this->getAverageTime(2,$from,$to,'',$request->account_id);
          $closedAverage = $this->getAverageTime(3,$from,$to,'',$request->account_id);
        }
    }

    if( Auth::user()->role_id == 7  ){
      $readAverage = $this->getAverageTime(1,$from,$to,'Case Read');
      $acceptedAverage = $this->getAverageTime(2,$from,$to);
      $closedAverage = $this->getAverageTime(3,$from,$to);
    }

    return json_encode(array('read'=>$readAverage,'accepted'=>$acceptedAverage,'closed'=>$closedAverage));
  }

  public function getReportActiveCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::with('participants')
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('status',1)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::with('participants')
            ->Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id',$request->user_id)
            ->whereBetween('cases.created_at', array($from, $to))
            ->where('cases.status',1)
            ->orderBy('cases.id','DESC')
            ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
            ->get();
    }

    $cases_arr = array();
    foreach($cases as $case)
    {
      $participants_arr = array();
      foreach($case->participants as $participant)
      {
        array_push($participants_arr, 
          array( 
            'user_id'=>$participant->user_id,
            'is_read'=>$participant->is_read,
            'ownership'=>$participant->ownership,
            'fname'=>$participant->user->fname,
            'lname'=>$participant->user->lname,
          )
        );
      }

      $cases_arr[] = array(
        "id"=>$case->id,
        "case_id" => $case->case_id,
        "call_type" => $case->call_type,
        "subcall_type"=> $case->subcall_type,
        "case_message" => $case->case_message,
        "status" => $case->status,
        "created_at" => $case->created_at,
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
    }

    return view('components.reports.report-active-case-list',['cases' => $cases_arr]);
  }

  public function getReportPendingCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::with('participants')
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('status',2)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::with('participants')
            ->Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id',$request->user_id)
            ->whereBetween('cases.created_at', array($from, $to))
            ->where('cases.status',2)
            ->orderBy('cases.id','DESC')
            ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
            ->get();
    }

    $cases_arr = array();
    foreach($cases as $case)
    {
      $participants_arr = array();
      foreach($case->participants as $participant)
      {
        array_push($participants_arr, 
          array( 
            'user_id'=>$participant->user_id,
            'is_read'=>$participant->is_read,
            'ownership'=>$participant->ownership,
            'fname'=>$participant->user->fname,
            'lname'=>$participant->user->lname,
          )
        );
      }

      $cases_arr[] = array(
        "id"=>$case->id,
        "case_id" => $case->case_id,
        "call_type" => $case->call_type,
        "subcall_type"=> $case->subcall_type,
        "case_message" => $case->case_message,
        "status" => $case->status,
        "created_at" => $case->created_at,
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
    }

    return view('components.reports.report-pending-case-list',['cases' => $cases_arr]);
  }

  public function getReportClosedCase(Request $request)
  {
    $r1 = explode("-", $request->range);
    $date1=date_create($r1[0]);
    $from = date_format($date1,"Y-m-d H:i:s");
    $date2=date_create($r1[1]);
    $to = date_format($date2,"Y-m-d H:i:s"); 

    if($request->user_id == "all"){
      $cases = Cases::with('participants')
              ->whereBetween('cases.created_at', array($from, $to))
              ->where('status',3)
              ->orderBy('id','DESC')
              ->get();
    }else{
      $cases = Cases::with('participants')
            ->Join('case_participants AS b','cases.id','=','b.case_id')
            ->where('b.user_id',$request->user_id)
            ->whereBetween('cases.created_at', array($from, $to))
            ->where('cases.status',3)
            ->orderBy('cases.id','DESC')
            ->select('cases.id','cases.case_id','cases.sender_fullname','cases.status','cases.created_at')
            ->get();
    }



    $cases_arr = array();
    foreach($cases as $case)
    {
      $participants_arr = array();
      foreach($case->participants as $participant)
      {
        array_push($participants_arr, 
          array( 
            'user_id'=>$participant->user_id,
            'is_read'=>$participant->is_read,
            'ownership'=>$participant->ownership,
            'fname'=>$participant->user->fname,
            'lname'=>$participant->user->lname,
          )
        );
      }

      $cases_arr[] = array(
        "id"=>$case->id,
        "case_id" => $case->case_id,
        "call_type" => $case->call_type,
        "subcall_type"=> $case->subcall_type,
        "case_message" => $case->case_message,
        "status" => $case->status,
        "created_at" => $case->created_at,
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
    }
    return view( 'components.reports.report-closed-case-list',[ 'cases' => $cases_arr ] );
  }

  public function getReportByCalltypes(Request $request)
  {
      $r1 = explode("-", $request->range);
      $date1=date_create($r1[0]);
      $from = date_format($date1,"Y-m-d H:i:s");
      $date2=date_create($r1[1]);
      $to = date_format($date2,"Y-m-d H:i:s"); 

      // $cases = Cases::whereBetween('cases.created_at', array($from, $to))
      //   ->account($request->account_id)
      //   ->calltype($request->call_type)
      //   ->subcalltype($request->subcall_type)
      //   ->orderBy('id','DESC')
      //   ->get();

      // return view('components.reports.report-by-calltypes',['cases' => $cases]);





    $cases = Cases::with('participants')
              ->whereBetween('cases.created_at', array($from, $to))
              ->account($request->account_id)
              ->calltype($request->call_type)
              ->subcalltype($request->subcall_type)
              ->orderBy('cases.status')
              ->orderBy('cases.id','DESC')
              ->get();

    $cases_arr = array();
    foreach($cases as $case)
    {
      $participants_arr = array();
      foreach($case->participants as $participant)
      {
        array_push($participants_arr, 
          array( 
            'user_id'=>$participant->user_id,
            'is_read'=>$participant->is_read,
            'ownership'=>$participant->ownership,
            'fname'=>$participant->user->fname,
            'lname'=>$participant->user->lname,
          )
        );
      }

      $cases_arr[] = array(
        "id"=>$case->id,
        "case_id" => $case->case_id,
        "call_type" => $case->call_type,
        "subcall_type"=> $case->subcall_type,
        "case_message" => $case->case_message,
        "status" => $case->status,
        "created_at" => $case->created_at,
        "updated_at" => $case->updated_at,
        "participants"=> $participants_arr
      );
      
    }


    $active_count = Cases::whereBetween('cases.created_at', array($from, $to))
                    ->account($request->account_id)
                    ->calltype($request->call_type)
                    ->subcalltype($request->subcall_type)
                    ->where('cases.status',1)
                    ->select(DB::raw('count(cases.id) as total'))
                    ->get();

    $pending_count = Cases::whereBetween('cases.created_at', array($from, $to))
                      ->account($request->account_id)
                      ->calltype($request->call_type)
                      ->subcalltype($request->subcall_type)
                      ->where('status',2)
                      ->select(DB::raw('count(*) as total'))
                      ->get();
    $closed_count = Cases::whereBetween('cases.created_at', array($from, $to))
                      ->account($request->account_id)
                      ->calltype($request->call_type)
                      ->subcalltype($request->subcall_type)
                      ->where('status',3)
                      ->select(DB::raw('count(*) as total'))
                      ->get();  

 
                     

    return view('components.reports.report-by-calltypes',[ 'cases' => $cases_arr,'active_count' => $active_count[0],'pending_count' => $pending_count[0],'closed_count' => $closed_count[0] ] );
  }

  public function reportsBilling(Request $request)
  {
    $accounts = Account::all();
    return view( 'admin-console-reports',['accounts'=>$accounts]);
  }

  public function reportsBillingTable(Request $request)
  {
    // $accounts = Account::whereIn('id',$request->account_id)->get();
    $users = User::leftJoin('accounts AS b','users.account_id','b.id')
                ->leftJoin("account_roles AS c",function($join){
                  $join->on('c.account_id','users.account_id')
                        ->on('c.role_id','users.role_id');
                })
                ->leftJoin('roles AS d','users.role_id','d.id')
                ->where('users.status','active')
                ->where('users.is_curacall',0) 
                ->whereIn('users.account_id',$request->account_id)
                ->select('users.fname','users.lname','d.role_title','users.date_activated','b.account_name','c.billing_rate')
                ->orderBy('users.account_id')
                ->orderBy('users.role_id')
                ->orderBy('users.fname')
                ->get(); 
    return view( 'components.reports.report-admin-billing',['users'=>$users,'billing_month'=>$request->billing_month."-01"]);
  }

}
