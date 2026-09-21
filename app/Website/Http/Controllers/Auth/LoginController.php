<?php

namespace App\Website\Http\Controllers\Auth;

use App\Website\Http\Controllers\Controller;
use App\Website\Models\Member;
use App\Website\Services\EmailService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:member')->except('logout');
        $this->middleware('auth:member')->only('logout');
    }

    public function showLoginForm()
    {
        $captcha = $this->generateCaptcha();

        return view('auth.login', compact('captcha'));
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $login = trim($request->username);
        $password = $request->password;
        $captcha = (int) $request->captcha;

        // Validate captcha first
        if ($captcha !== (int) session('captcha_answer')) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid captcha.',
                'captcha' => $this->generateCaptcha(),
            ], 422);
        }

        // Find member
        $member = Member::where('email', $login)
            ->orWhere('mobile_number', $login)
            ->orWhere('profile_id', $login)
            ->first();

        // Validate credentials
        if (! $member || ! hash_equals((string) $member->password, $password)) {

            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
                'captcha' => $this->generateCaptcha(),
            ], 401);
        }

        // Login
        Auth::guard('member')->login($member, $request->boolean('remember'));

        $request->session()->regenerate();

        // Generate new captcha for next login
        $this->generateCaptcha();

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully.',
            'redirect' => route('home'),
        ]);
    }

    public function initial_registor(Request $request, EmailService $emailService)
    {
        $request->validate(['password' => 'required|string|min:6']);

        if ((int) $request->captcha !== (int) session('captcha_answer')) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid captcha.',
                'captcha' => $this->generateCaptcha(),
            ], 422);
        }

        $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => $request->password,
            'profile_created_for' => $request->profile_created_for,
            'gender' => $request->gender,
            'birth_date_time' => $request->birth_date,
            'registration_date' => now(),
            'profile_id' => 'NA',
            'profile_completed' => '15%',
        ];

        $data = array_filter($data, fn ($value) => ! is_null($value));
        // dd($data);
        $member = Member::create($data);
        //  dd($member->toArray());
        $profile_id = 10000 + $member->id;
        $member->update(['profile_id' => 'HIM'.$profile_id]);
        Auth::guard('member')->login($member);
        $request->session()->regenerate();
        $this->generateCaptcha();

        // $emailService->sendRegisterEmail($member);
        return response()->json(['success' => true, 'redirect' => 'complete-profile', 'message' => 'Registration successful!']);
        // return redirect()->route('home')->with('success', 'Registration successful!');
    }

    public function google_signup()
    {
        return Socialite::driver('google')->redirect();
    }

    public function google_signup_callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $member = Member::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (! $member) {

            $member = Member::create([
                'full_name' => $googleUser->name,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => Str::random(32),
                'profile_id' => 'NA',
                'registration_date' => now(),
                'profile_completed' => '15%',
            ]);

            $profile_id = 10000 + $member->id;

            $member->update([
                'profile_id' => 'HIM'.$profile_id,
            ]);
        } else {

            // update google id if old account exists
            if (empty($member->google_id)) {

                $member->update([
                    'google_id' => $googleUser->id,
                ]);
            }
        }

        Auth::guard('member')->login($member);

        request()->session()->regenerate();

        return redirect()->route('home');
    }

    public function logout()
    {
        Auth::guard('member')->logout();

        return redirect()->route('login-form');
    }

    public function generateCaptcha()
    {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);

        session([
            'captcha_answer' => $num1 + $num2,
        ]);

        return "{$num1} + {$num2}";
    }
}
