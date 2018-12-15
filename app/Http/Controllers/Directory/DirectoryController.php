<?php
namespace App\Http\Controllers\Directory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use DB;
use Auth;

class DirectoryController extends Controller
{

  public function index()
  {
  	$data = User::leftJoin('roles AS b','users.role_id','=','b.id')
  						->leftJoin('accounts AS c','users.account_id','=','c.id')
  						->where( 'users.status', 'active' )
  						->IsCuraCall() 
  						->select( 'users.*','b.role_title','c.account_name' )
  						->orderBy( 'users.role_id')
  						->get(); 
    return view( 'directory',['data' => $data]);
  }
}
