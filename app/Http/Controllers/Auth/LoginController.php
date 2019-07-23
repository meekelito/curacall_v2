<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;
use Auth;
use Validator;
use Session;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\EmailVerification;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;
    private $quota_limit = 5;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = 'all-messages'; 

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() 
    {
      $this->middleware('guest', ['except' => ['logout', 'userActivation']]);
    }

    public function showEmailForm()
    {
      // $request->session()->forget('key');
      return view('auth.login-email');
    }

    public function showPasswordForm()
    {

      if (Session::has('login_email')) {
         $ip_address = $this->getUserIP();
          $re_captcha = false;    
            if(Cache::has($ip_address)){
              if(intval(Cache::get($ip_address)) <= 0)
                $re_captcha = true;
            }
        return view('auth.login-password',['re_captcha'=>$re_captcha]);
      }else{
        return redirect('/login');
      } 
    }


    public function loginEmail(Request $request)
    { 
      $validator = Validator::make($request->all(), [
        'email' => 'required|email',
      ]);

      if ($validator->fails()) {
        return back()->withInput()->with('warning', 'Couldn\'t find your CuraCall Account');
      }

      $data = User::where( 'email', $request->input('email') )->get();

      if( !$data->isEmpty() && $data[0]->status == 'active' ){
        session(['login_email' => $request->input('email')]);
        return redirect('login/password');
      }else if( !$data->isEmpty() && $data[0]->status == 'deactivated' ){
        return back()->withInput()->with('warning', 'Your Account has been deactivated. Please contact CuraCall admin.');
      }else if( !$data->isEmpty() && $data[0]->status == 'pending' ){
        return back()->withInput()->with('warning', 'Your Account is not yet active. Please contact CuraCall admin.');
      }else{
        return back()->withInput()->with('warning', 'Couldn\'t find your CuraCall Account');
      }
    }

    public function login(Request $request)
    {
      $email = Session::get('login_email');
      $password = $request->input('password');
      $ip_address =$this->getUserIP();

      $request->validate([
        'password' => 'required'
      ]);

      $available = $this->quotaPerDay($ip_address);

      if(!$available){
             $validator = \Validator::make($request->all(), [
                  'g-recaptcha-response' => 'required|captcha'
              ],[
                  'g-recaptcha-response.required'=>'Please check the Captcha.'
              ]);

           if($validator->fails())
                 return back()->withErrors($validator)->withInput()->with('warning', 'Please check the Captcha');
        }

      if (Session::has('login_email') && Auth::attempt(['email' => $email, 'password' => $password, 'status' => 'active'])) {
        $user = User::find(Auth::user()->id);
        $user->timezone = $request->tz;
        $user->save();
        Cache::forget($ip_address);
        return redirect()->intended('/dashboard');
      }else{
        return back()->withInput()->with('warning', 'You have entered an invalid password');
      }

    }


    public function logout () {
      // logout user
      auth()->logout();
      Session::flush();
      return redirect('/');
    }

    function quotaPerDay($ip)
    {
        if(!$this->quota_limit)
          return true;

        if(Cache::has($ip)){
           if(intval(Cache::get($ip)) <= 0)
              return false;
           Cache::decrement($ip);
        }
        else
           Cache::put($ip,$this->quota_limit, Carbon::now()->addDay());
               
        
        return Cache::get($ip);
    }

     function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    } 


    public function userActivation($token,Request $request)
    {
        if(Auth::user())
            Auth::logout();

        // $verify_attempt = Cache::get('verify_attempt');

        // if(intval($verify_attempt) >= 10)
        // {
        //     DB::insert("INSERT INTO malicious_logs(ip,user_agent,url,created_at) VALUES(?,?,?,?)",array(Helpers::getUserIP(),$request->header('User-Agent'),$request->fullUrl(),date("Y-m-d H:i:s")));
        //     return "Too Many Attempts.";
        // }

       // $check = DB::table('email_verifications')->where(DB::raw('BINARY `token`'), $token)->first();
        $check = EmailVerification::where('token',$token)->firstOrFail();

        if(!is_null($check)){
            $user = User::find($check->user_id);

            if($user->is_activated == 1){
                return redirect()->to('login')
                    ->with('success',"Your account is already activated.");                
            }

            // $user->update(['is_verified' => 1]);
            // DB::table('email_verifications')->where('token',$token)->delete();

            //return redirect()->to('login')->with('success',"Your account has been activated.");
            //Cache::forget('verify_attempt');
            Auth::loginUsingId($check->user_id);
            return redirect()->route('user.newpassword');
        }

        // if(!Cache::has('verify_attempt'))
        // {
        //     $expiresAt = Carbon::now()->addMinutes(30);
        //     Cache::put('verify_attempt', 1, $expiresAt);
        // }else
        // {
        //     Cache::increment('verify_attempt');
        // }

        return redirect()->to('login');
    }
}
