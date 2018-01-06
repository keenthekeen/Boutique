<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Socialite;

class LoginController extends Controller {
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
    protected $redirectTo = '/home';
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest')->except('logout');
    }
    
    /**
     * Redirect the user to the GitHub authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider() {
        return Socialite::driver('facebook')->setScopes(['email'])->redirect();
    }
    
    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback() {
        try {
            $socialUser = Socialite::driver('facebook')->user();
        } catch (\Exception $e) {
            \Log::error('User cannot be logged in: '. $e->getMessage());
            return response()->view('errors.custom', ['title' => 'Error while logging in', 'description' => 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่', 'button' => '<a href="/login" class="waves-effect waves-light btn indigo darken-3 tooltipped center-align" data-tooltip="Back to index"
       style="width:80%;max-width:350px;margin-top:20px">ลองใหม่</a>']);
        }
        
        if (!$user = User::find($socialUser->getId())) {
            if (empty($socialUser->getEmail())) {
                return response()->view('errors.custom', ['title' => 'ไม่สามารถเข้าสู่ระบบได้', 'description' => 'ไม่ได้รับอีเมลของผู้ใช้จาก Facebook']);
            }
            $user = User::create([
                'id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'avatar' => $socialUser->getAvatar()
            ]);
        }
        Auth::login($user);
        
        return redirect()->intended();
    }
    
    public function logout(Request $request) {
        $request->session()->invalidate();
        
        return redirect()->home();
    }
}
