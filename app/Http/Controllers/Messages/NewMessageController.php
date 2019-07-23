<?php
namespace App\Http\Controllers\Messages;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Room;
use App\Participant;
use Auth;
use DB;
use Validator;

class NewMessageController extends Controller
{

  public function index()
  {
  	// $users = User::where('id','!=',Auth::user()->id)
   //              ->where('status','active')
   //              ->orderBy('fname')
   //              ->get();

    if( Auth::user()->is_curacall ){
      $users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->orderBy('fname')
                ->get();
    }else{
      $users = User::where('id','!=',Auth::user()->id)
                ->where('status','active')
                ->where('account_id',Auth::user()->account_id)
                ->orderBy('fname')
                ->get();
    }

    $rooms = Participant::where('user_id',Auth::user()->id)
              ->select('room_id')
              ->get();

    $participants = Participant::leftJoin('rooms AS b','participants.room_id','=','b.id')
              ->leftJoin('users AS c','participants.user_id','=','c.id')
              ->whereIn('room_id',$rooms)
              ->whereNotNull('b.last_message')
              ->where('participants.user_id','!=',Auth::user()->id)
              ->select('participants.room_id','b.participants_no','c.id','c.prof_img','c.fname','c.lname')
              ->orderBy('b.updated_at','desc')
              ->orderBy('participants.id')
              ->get(); 

    return view('new-message',[ 'users'=>$users,'rooms'=>$rooms,'participants'=>$participants ] );
  }



  public function createRoom(Request $request)
  {
    
    $recipient = $request->input("recipient");

    array_push($recipient,strval(Auth::user()->id));

    sort($recipient);
    $room_name = implode("-",$recipient);

    $rooms = Room::select('id')->where('name',$room_name)->get();

    

    if ($rooms->isNotEmpty()){
      return redirect('messages/room/'.$rooms[0]->id);
    }else{
      $user = Auth::user();
      DB::beginTransaction(); 
      try{ 
        $room_id = Room::create(['name'=>$room_name, 'user_id' => Auth::user()->id ,'participants_no' => count($recipient)])->id;
 
        $participants = array();

        for( $i = 0; $i <= count($recipient)-1; $i++ ){
          $participants[] = array('room_id' => $room_id, 'user_id' => $recipient[$i], 'is_read' => 0);
        }
        Participant::insert($participants);

        DB::commit();

        return redirect('messages/room/'.$room_id);
      } catch (Exeption $e){
        DB::rollback();
        return json_encode(array(
          "status"=>0,
          "response"=>"failed", 
          "message"=>"Error in connection."
        ));
      }
    }

  }

  // public function addparticipant(Request $request)
  // {
  //   $validator = Validator::make($request->all(),[ 
  //     'room_id' => 'required|exists:rooms,id', 
  //     'recipients' => 'required'
  //   ]);

  //   if( $validator->fails() ){
  //     return response()->json([ 
  //       "status"=> 400,
  //       "response"=>"bad request", 
  //       "message"=>$validator->errors()
  //     ]);
  //   }

  //   $recipients = $request->input("recipients");

  //   array_push($recipients,strval(Auth::user()->id));

    
  //   $chat_participants = Participant::select('user_id')->where('room_id',$request->room_id)->get();
  //   $existing_participants = array();

  //   foreach($chat_participants as $row)
  //   {
  //       $existing_participants[] = $row->user_id;
  //   }

  //   $recipients = array_unique (array_merge ($request->recipients, $existing_participants));
  //   sort($recipients);
  //   $room_name = implode("-",$recipients);

  //   $rooms = Room::select('id')->where('name',$room_name)->first();
  //   if($rooms)
  //   {
  //      return redirect('messages/room/'.$rooms->id);
  //   }else{
  //         DB::beginTransaction(); 
  //         try{ 
  //           Room::where('id',$request->room_id)->update(['name'=>$room_name ,'participants_no' => count($recipients)]);
     
  //           $participants = array();

  //           for( $i = 0; $i <= count($recipients)-1; $i++ ){
  //             $participants[] = array('room_id' => $request->room_id, 'user_id' => $recipients[$i], 'is_read' => 0);
  //           }
  //           Participant::where('room_id',$request->room_id)->delete();
  //           Participant::insert($participants);

  //           DB::commit();

  //           return redirect('messages/room/'.$request->room_id);
  //         } catch (Exeption $e){
  //           DB::rollback();
  //           return json_encode(array(
  //             "status"=>0,
  //             "response"=>"failed", 
  //             "message"=>"Error in connection."
  //           ));
  //         }
  //   }
    
    
  // }

  public function createMessage(Request $request)
  {
    $user = Auth::user();

  	$recipient = $request->input("recipient");
    $message = $request->input("message");
    
    DB::beginTransaction(); 
    try{
      $room_id = Room::create([ 'user_id' => Auth::user()->id ])->id;

      $participants = array(array('room_id' => $room_id, 'user_id' => Auth::user()->id, 'is_read' => 1));

      for( $i = 0; $i <= count($recipient)-1; $i++ ){
        $participants[] = array('room_id' => $room_id, 'user_id' => $recipient[$i], 'is_read' => 0);
      }

      Participant::insert($participants);
      $message = $user->messages()->create([
        'room_id' => $room_id, 
        'message' => $request->input('message')
      ]);

      Room::find( $room_id )->update(['last_message' => $message->id]);

      DB::commit();
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully sent"
      ));
    } catch (Exeption $e){
      DB::rollback();
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
     
  }

}
