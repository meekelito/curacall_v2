<?php
namespace App\Http\Controllers\Cases;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Cases;
use App\Note;
use DataTables;
use DB;
use Cache;
use Auth;
use Validator;

class NewCaseController extends Controller
{
  public function index($case_id) 
  {	
  	$case_status = Cases::where('id',$case_id)->get();

  	if( $case_status[0]->status == 1 ){
	  	$res = Cases::find($case_id);
	    $res->status = 2;
	    $res->save();
  	}

    return view( 'cases', [ 'case_id' => $case_id,'case_status' => $case_status[0]->status ] );
  }

  public function fetchNotes($case_id) 
  {    
    $note = Note::leftJoin('users AS b','notes.created_by','=','b.id')
    				->where('notes.case_id',$case_id)
    				->orderBy('notes.id','DESC')
    				->select('notes.id','notes.note','notes.created_at','b.prof_img','b.lname','b.fname');

    return Datatables::of($note)
    ->addColumn('note',function($note){
      $petsa = "";
      $content = "";
      if( date('Y-m-d') == date('Y-m-d', strtotime($note->created_at))){
        $petsa = date_format($note->created_at,"h:i a");
      }else{
        $petsa = date_format($note->created_at,"M d") ;
      }
      if( strlen($note->note) >= 50){
        $content = substr($note->note, 0, 50).'...';
      }else{
        $content = $note->note;
      }

      return '<div class="media-left">
      <img src="'.asset('storage/uploads/users/'.$note->prof_img).'" class="img-circle img-xs" alt="">
      </div>
      <div class="media-body" style="width: 100%;">
        <a onclick="view_note('.$note->id.')">
        '.$note->fname.' '.$note->lname.'
        <span class="media-annotation pull-right">
        '.$petsa.'
        </span>
        </a>
        <span class="display-block text-muted">'.$content.'</span>
      </div>';
    })
    ->rawColumns(['note'])
    ->make(true);                                                               
  } 

  public function getModalForwardCase() 
  {							 
  	$users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->orderBy('fname') 
                ->get();  	
    return view('components.cases.forward-case-md',[ 'users'=>$users ]); 
  }

  public function getModalCloseCase(Request $request) 
  {							 
    $case_id = $request->input('case_id');
    return view('components.cases.close-case-md',['case_id' => $case_id]); 
  }

  public function getModalAddNote(Request $request) 
  {							 
  	$case_id = $request->input('case_id');
    return view('components.cases.add-note-md',['case_id' => $case_id]); 
  }

  public function getModalViewNote(Request $request) 
  {            
    $note = Note::leftJoin('users AS b','notes.created_by','=','b.id')
            ->where('notes.id',$request->input('id'))
            ->select('notes.id','notes.note','notes.created_at','b.prof_img','b.lname','b.fname')
            ->get();

    return view('components.cases.view-note-md',['note' => $note]); 
  }

  public function newNote(Request $request)
  {


  	$validator = Validator::make($request->all(), [
        'case_id' => 'required',
        'note' => 'required',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    if( $request->case_form == "close" ){
    	$res = Cases::find($request->case_id);
	    $res->status = 3;
	    $res->save();
    }

  	$res = Note::create( $request->all()+[ 'created_by' => Auth::user()->id ] ); 

  	if($res){
  		return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Note successfully added."
      ));
  	}else{
  		return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
  	}
  }

}
