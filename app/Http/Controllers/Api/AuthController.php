<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\User;
use JWTAuth;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'check_email']]);
    }

    public function timeInMinutes($user) {
        if (!$user) {
            return Carbon::now()->addMinutes(15)->timestamp;
        }
        if ($user->logoff_time == 15 ) {
            return Carbon::now()->addMinutes(15)->timestamp;
        }
        if (!$user->logoff_time) {
            return Carbon::now()->addDays(30)->timestamp;
        }
        $days = $user->logoff_time;
        if ($user->logoff_time == 24 ){
            $days = 1;
        }
        
        if ($days == 1 || $days == 7 ) {
            return Carbon::now()->addDays($days)->timestamp;
        }


    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {   
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);

        
        // $userInfo = User::where('email', $request->email)->first();
        // $time = $this->timeInMinutes($userInfo);
        // if ($request->mobile_pin) {
        //     $user = User::where('email', $request->email)->where('mobile_pin',$request->mobile_pin)->first();
        //     $token = JWTAuth::fromUser($user, ['exp' => $time]);
        // } else {
        //     $credentials = request(['email', 'password']);
        //     $token = auth('api')->attempt($credentials, ['exp' => $time]);
        // }

        // if (!$token) {
        //     return response()->json(['error' => 'Password did not match']);
        // }

        // return $this->respondWithToken($token, $time, $userInfo);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth('api')->user());
    }

    /**
     * Get the user User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function check_email(Request $request)
    {
        $user = User::where('email', $request->email)->count();
        return response()->json(['count'=>$user, 'email'=>$request->email]);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        if (auth('api')) auth('api')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token, $time = '', $user = '')
    {
        if (auth('api')->user()) {
            $theUser = auth('api')->user();
        } else {
            $theUser = $user;
        }

        return response()->json([
            'token' => $token,
            'logoff_time' => $time,
            'logoff_date' => date('Y-m-d H:i:s', $time),
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user'=>$theUser
        ]);
    }
}
