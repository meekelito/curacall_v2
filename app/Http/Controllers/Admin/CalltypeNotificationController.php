<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Calltype_notification;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Crypt;
use Validator;

class CalltypeNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('escalation-settings.index');
    }

    public function getdata()
    {
        $role = Calltype_notification::with('call_type');
        return Datatables::of($role)
        ->make(true); 
    }
    
    public function updateinterval(Request $request)
    {
       $validator = Validator::make($request->all(),[ 
          'id' => 'required',
          'interval' => 'required|integer'
        ]);

        if( $validator->fails() ){
          return response()->json([ 
            "status" => 0,
            "response"=>"Invalid parameters", 
            "message"=>$validator->errors()
          ],406);
        }

        $calltype = Calltype_notification::where('calltype_id',$request->id)->first();

        if($calltype){
            $calltype->interval_minutes = $request->interval;
            $calltype->save();

          return response()->json([ 
            "status" => 1,
            "response"=>"Success", 
            "message"=> "Successfully updated"
          ]);
        }

        return response()->json([ 
            "status" => 0,
            "response"=>"Error", 
            "message"=> "Something went wrong"
          ]);
    }

    public function updatecron(Request $request)
    {
       $validator = Validator::make($request->all(),[ 
          'id' => 'required',
          'data' => 'required'
        ]);

        if( $validator->fails() ){
          return response()->json([ 
            "status" => 0,
            "response"=>"Invalid parameters", 
            "message"=>$validator->errors()
          ],406);
        }

        $calltype = Calltype_notification::where('calltype_id',$request->id)->first();

        if($calltype){
            $calltype->cron_settings = json_encode($request->data);
            $calltype->save();

          return response()->json([ 
            "status" => 1,
            "response"=>"Success", 
            "message"=> "Successfully updated"
          ]);
        }

        return response()->json([ 
            "status" => 0,
            "response"=>"Error", 
            "message"=> "Something went wrong"
          ]);
    }


    public function show(Request $request)
    {
        $result = Calltype_notification::where('calltype_id',$request->id)->first();

        return response()->json($result);
    }
}
