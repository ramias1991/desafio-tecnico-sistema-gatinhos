<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function index(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
        $creds = $request->only(['email', 'password']);
        $creds_jwt = Http::asForm()->post("https://" . env('LOCAL_IP') ."/api/login", $creds);

        if(Auth::attempt($creds) && $creds_jwt) {
            $creds_jwt = json_decode($creds_jwt);
            $token = $creds_jwt->access_token;
            setcookie('access_token', $token, 0, "", "", true, true);
            return redirect()->route('home', [], 302);
        } else {
            return redirect()->route('login', [], 302)->with('warning', 'E-mail e/ou senha inválido.');
        }
    }

    public function logout($msg) {
        Auth::logout();
        Cache::forget('listCats');
        Cache::forget('listCatsApi');
        return redirect()->route('login')->with('success', $msg);
    }
}
