<?php
namespace App\Http\Controllers\Messages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Room;
use App\Participants;
use App\Messages;
use App\Users;
use DB;
use Cache;
use Auth;

class ClosedMessagesController extends Controller
{

  public function index()
  {
  	$messages = Room::leftJoin('participants AS b','rooms.id','=','b.room_id') 
  						->leftJoin('messages AS c','rooms.last_message','=','c.id')
  						->leftJoin('users AS d','c.user_id','=','d.id')
  						->where('b.user_id',Auth::user()->id)
              ->where('b.is_read',1)
              ->where('rooms.status','closed')
  						->select('rooms.id AS room_id','c.message','c.created_at','d.fname','d.lname','d.prof_img')
  						->groupBy('rooms.id')
  						->get(); 

    return view( 'closed-messages',[ 'messages' => $messages ] );
  }

  public function closeMessage(Request $request)
  {
    $user = Auth::user();
    
    $res = Room::find( $request->room_id )->update(['status' => 'closed']);

    $message = $user->messages()->create([
      'room_id' => $request->room_id, 
      'message' => $request->note
    ]);

    return json_encode(array(
      "status"=>1,
      "response"=>"success",
      "message"=>"Successfully closed."
    ));
  }
}
