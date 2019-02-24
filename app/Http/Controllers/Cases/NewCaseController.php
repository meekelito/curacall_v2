<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use App\Participants;
use App\Messages;
use App\Users;
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
}
