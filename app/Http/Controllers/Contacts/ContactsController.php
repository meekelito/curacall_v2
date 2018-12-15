<?php
namespace App\Http\Controllers\Contacts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Account_role;
use DB;
use DataTables;
use Cache;
use Auth;

class ContactsController extends Controller
{

  public function index()
  {
    return view( 'contacts');
  }

  public function fetchContacts()  
  {  
    // $users = User::leftJoin('roles AS b','users.role_id','=','b.id')
    //         ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title')
    //         ->where('users.status','active')
    //         ->IsCuraCall() 
    //         ->where('users.id','!=',Auth::user()->id);

    if( Auth::user()->is_curacall ){
      $users = User::leftJoin('roles AS b','users.role_id','=','b.id')
            ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title')
            ->where('users.status','active')
            ->IsCuraCall() 
            ->where('users.id','!=',Auth::user()->id);
    }else{
      $contacts = Account_role::where('account_id','=', Auth::user()->account_id)
                  ->where('role_id','=', Auth::user()->role_id)
                  ->get();

      if( $contacts[0]->msg_all ){
        $users = User::leftJoin('roles AS b','users.role_id','=','b.id')
            ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title')
            ->where('users.status','active')
            ->IsCuraCall()  
            ->where('users.id','!=',Auth::user()->id);
      }else if( $contacts[0]->msg_management ){
        $users = User::leftJoin('roles AS b','users.role_id','=','b.id')
            ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title')
            ->where( 'users.status', 'active' )
            ->where( 'users.role_id', 5)
            ->where( 'users.id', '!=', Auth::user()->id );
      }else{
        $users = User::leftJoin('roles AS b','users.role_id','=','b.id')
            ->select('users.id','users.fname','users.lname','users.phone_no','users.email','users.prof_img','b.role_title')
            ->where('users.status','active')
            ->IsCuraCall() 
            ->where('users.id','!=',Auth::user()->id);
      }
 
                
    }
    

    return Datatables::of($users)
    
    ->editColumn('id',function($users){
      return 'CC'.str_pad($users->id, 6,'0',STR_PAD_LEFT);
    }) 
    ->editColumn('email',function($users){
      return "<a href='#'>".$users->email."</a>";
    }) 
    ->addColumn('pic',function($users){
      if( file_exists('storage/uploads/users/'.$users->prof_img) ){
        return '<img src="'.asset('storage/uploads/users/'.$users->prof_img).'" class="img-circle img-md">'; 
      }else{
        return '<img src="'.asset('storage/uploads/users/default.png').'" class="img-circle img-md">'; 
      }
    }) 
    ->addColumn('action', function ($users) {
      return '<a href="#" class="label bg-slate label-rounded label-icon" title="Message"><i class="icon-comment"></i></a>'; 
    })
    ->rawColumns(['pic','name','email','action'])
    ->make(true);                                                                                
  } 
}
