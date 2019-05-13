<?php
namespace App\Http\Controllers\UserAccountSettings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\State;
use App\User;
use DataTables;
use DB;
use Cache;
use Auth;
use Hash;
use Validator;

class UserAccountSettingsController extends Controller
{

  public function index()
  {
    $state = State::all();
    $data = User::where( 'id', Auth::user()->id)->get();
    return view( 'user-account-settings',['data' => $data,'state' => $state]);
  }

  public function updateUser(Request $request)  
  {  
    $validator = Validator::make($request->all(), [
        'fname' => 'required|regex:/^[\pL\s\-]+$/u',
        'lname' => 'required|regex:/^[\pL\s\-]+$/u',
        'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        'mobile_no' => 'nullable|string',
        'phone_no' => 'nullable|string',
        'image' => 'nullable|max:1024|mimes:jpg,jpeg,png', 
    ],[
      'fname.required'=>'First name is required.',
      'fname.regex' => 'The first name may only contain letters and spaces.',
      'lname.regex' => 'The last name may only contain letters and spaces.',
      'image.mimes' => 'The image must be a file of type: jpeg or png.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }


    if( $request->hasFile('image') ){
      $filename=strtotime('now').'photo.jpg';
      $directory = "users";
      
      Storage::putFileAs($directory, $request->file('image'), $filename);

      if( $request->file('image')->isValid() ){
        $res = User::find( Auth::user()->id )->update($request->all()+['prof_img' => $filename ]+['updated_by' => Auth::user()->id ]);
        // unlink('storage/uploads/users/'.Auth::user()->prof_img);
      }
      else{ 
        return json_encode(array(
          "status"=>2,
          "response"=>"success",
          "message"=>"Theres a problem uploading the image."
        ));
      }

    }else{
      $res = User::find( Auth::user()->id )->update($request->all()+['updated_by' => Auth::user()->id ]);
    }

    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Successfully saved. \n You may need to refresh the page to see the changes."
      ));
    }else{
      return json_encode(array(
        "status"=>0,
        "response"=>"failed", 
        "message"=>"Error in connection."
      ));
    }
  }

  public function updateUserCredentials(Request $request)  
  {  
    $validator = Validator::make($request->all(), [
      'current_password' => 'bail|required|min:8',
      'password'  => 'bail|required|min:8|confirmed|different:current_password|regex:/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
    ],[
      'password.different'=>'The new password and current password must be different.',
    ]);

    if ($validator->fails()) {
      return json_encode(array(
        "status"=>2,
        "response"=>"error",
        "message"=>$validator->errors()
      ));
    }

    if (Hash::check($request->input('current_password'), Auth::user()->password )) {

      $res = User::find(Auth::user()->id);
      $res->password = Hash::make($request->input('password'));
      $res->updated_by = Auth::user()->id;
      $res->save();

    }else{
      return json_encode(array(
        "status"=>3,
        "response"=>"failed", 
        "message"=>"Incorrect Password!"
      ));
    }

    if($res){ 
      return json_encode(array(
        "status"=>1,
        "response"=>"success",
        "message"=>"Password successfully updated."
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
