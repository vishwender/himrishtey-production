<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RelationshipManagerAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $first = random_int(1, 9);
        $second = random_int(1, 9);
        $request->session()->put('admin_login_captcha', [
            'answer' => $first + $second,
            'expires_at' => now()->addMinutes(30)->timestamp,
        ]);

        return view('admin.auth.login', ['captcha' => "{$first} + {$second}"]);
    }

    public function login(Request $request)
    {
        $challenge = $request->session()->pull('admin_login_captcha');
        $request->validate([
            'captcha' => ['bail', 'required', 'integer', function ($attribute, $value, $fail) use ($challenge) {
                if (! is_array($challenge)
                    || ($challenge['expires_at'] ?? 0) <= now()->timestamp
                    || (int) $value !== ($challenge['answer'] ?? null)) {
                    $fail('Incorrect or expired captcha. Please answer the new question.');
                }
            }],
        ], [
            'captcha.required' => 'Please enter the captcha answer.',
            'captcha.integer' => 'The captcha answer must be a whole number.',
        ]);

        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {

            $request->session()->regenerate();

            $admin = Auth::guard('admin')->user();

            if (! $admin->status) {
                Auth::guard('admin')->logout();

                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'email' => 'Your admin account is inactive.',
                ]);
            }

            if ($admin->isSiteAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            if (app(RelationshipManagerAccess::class)->isRestricted()) {
                return redirect()->route('admin.members.index');
            }

            if ($admin->hasRole('content-manager') && ! $admin->hasRole('super-admin')) {
                return redirect()->route('admin.blog-posts.index');
            }

            return redirect()->intended(
                route('admin.dashboard')
            );
        }

        return back()
            ->withErrors([
                'email' => 'Invalid email or password.',
            ])
            ->onlyInput('email');
    }

    public function editPassword()
    {
        return view('admin.settings.change-password');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password:admin'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
        ]);

        $admin = $request->user('admin');
        $admin->password = $validated['password'];
        $admin->setRememberToken(Str::random(60));
        $admin->save();

        $request->session()->regenerate();

        return redirect()->route('admin.settings.password.edit')
            ->with('success', 'Your password has been changed successfully.');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
