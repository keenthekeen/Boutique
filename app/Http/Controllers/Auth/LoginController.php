<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Auth;
use Exception;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Log;
use Socialite;
use Symfony\Component\HttpFoundation\Response;

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
     * @return Response
     */
    public function redirectToProvider(): Response {
        return Socialite::driver('facebook')->setScopes(['email'])->redirect();
    }
    
    /**
     * Obtain the user information from GitHub.
     *
     * @param Request $request
     * @return Response
     */
    public function handleProviderCallback(Request $request): Response {
        try {
            $socialUser = Socialite::driver('facebook')->user();
        } catch (Exception $e) {
            Log::error('User cannot be logged in: ' . get_class($e) . ' (' . $e->getMessage() . ')');
            
            return response()->view('errors.custom', [
                'title' => 'Error while logging in',
                'description' => 'ไม่สามารถเข้าสู่ระบบได้ กรุณาลองใหม่',
                'button' => '<a href="/login" class="waves-effect waves-light btn indigo darken-3 tooltipped center-align" data-tooltip="Back to index"
       style="width:80%;max-width:350px;margin-top:20px">ลองใหม่</a>'
            ]);
        }
        
        if (!$user = User::find($socialUser->getId())) {
            if (empty($email = $socialUser->getEmail())) {
                $email = $socialUser->getId() . '@facebook.com';
                // return response()->view('errors.custom', ['title' => 'ไม่สามารถเข้าสู่ระบบได้', 'description' => 'ไม่ได้รับอีเมลของผู้ใช้จาก Facebook']);
            }
            $user = User::create([
                'id' => $socialUser->getId(),
                'name' => $socialUser->getName(),
                'email' => $email,
                'avatar' => $socialUser->getAvatar()
            ]);
        }
        Auth::login($user);

        if ($request->session()->has('is_merchant')){
            $request->session()->remove('is_merchant');

            $user->is_merchant = true;
            $user->save();
        }

        Log::info('User '.$socialUser->getId(). ' ('.$socialUser->getName().') logged in from '.$request->ip());
        
        return redirect()->intended();
    }
    
    public function logout(Request $request): Response
    {
        $request->session()->invalidate();
        
        return redirect()->home();
    }
}
