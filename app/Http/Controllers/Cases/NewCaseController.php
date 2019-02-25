<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use DB;
use Cache;
use Auth;

class NewCaseController extends Controller
{
  public function index($case_id) 
  {							  	
    return view( 'cases', [ 'case_id' => $case_id ] );
  }

  public function getModalForwardCase() 
  {							 
  	$users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->orderBy('fname') 
                ->get();  	
    return view('components.cases.forward-case-md',[ 'users'=>$users ]); 
  }

   public function getModalCloseCase() 
  {							 
    return view('components.cases.close-case-md'); 
  }
}
