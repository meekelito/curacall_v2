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
      $this->middleware('guest')->except('logout');
    }

    public function showEmailForm()
    {
      // $request->session()->forget('key');
      return view('auth.login-email');
    }

    public function showPasswordForm()
    {

      if (Session::has('login_email')) {
        return view('auth.login-password');
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

      $request->validate([
        'password' => 'required'
      ]);

      if (Session::has('login_email') && Auth::attempt(['email' => $email, 'password' => $password, 'status' => 'active'])) {
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
}
