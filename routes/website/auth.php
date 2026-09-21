<?php

use App\Website\Http\Controllers\Auth\ForgotPasswordController;
use App\Website\Http\Controllers\Auth\LoginController;
use App\Website\Http\Controllers\MemberController;
use App\Website\Http\Controllers\OtpController;
use Illuminate\Support\Facades\Route;

Route::post('initial-register', [LoginController::class, 'initial_registor'])->name('initial-register');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login-form');
Route::post('member-login', [LoginController::class, 'login'])->name('member-login');
Route::post('member-logout', [LoginController::class, 'logout'])->name('member-logout')->middleware('auth:member');
Route::get('google-signup', [LoginController::class, 'google_signup'])->name('google-signup');
Route::get('google-signup-callback', [LoginController::class, 'google_signup_callback'])->name('google-signup-callback');

Route::post('checkMemberExist', [MemberController::class, 'checkMemberExist'])->name('checkMemberExist');
Route::match(['GET', 'POST'], '/complete-profile', [MemberController::class, 'completeProfile'])->name('complete-profile');

Route::post('/send-otp', [OtpController::class, 'sendOtp'])->name('send-otp');
Route::post('/verify-otp', [OtpController::class, 'verifyOtp'])->name('verify-otp');
Route::post('/login-with-otp', [OtpController::class, 'login_otp'])->name('login-with-otp');
Route::post('/verify-login-otp', [OtpController::class, 'verifyLoginOtp'])->name('verify-login-otp');
Route::post('/callback-request', [OtpController::class, 'callbackRequest'])->name('callback.request');
Route::get('/callback-status', [OtpController::class, 'callbackStatus'])->name('callback.status');
Route::post('/verify-account-request', [OtpController::class, 'verify_account_request'])->name('verify_account_request');

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPassword'])->name('forgot.password');
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOtp'])->name('forgot.password.send.otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOtp'])->name('forgot.password.verify.otp');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot.password.reset');
