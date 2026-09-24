<?php

use App\Website\Http\Controllers\BlogController;
use App\Website\Http\Controllers\ContactController;
use App\Website\Http\Controllers\SitemapController;
use App\Website\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/design-preview', [WelcomeController::class, 'designPreview'])->name('design-preview');
Route::get('about-us', [WelcomeController::class, 'about'])->name('about-us');
Route::get('success-stories', [WelcomeController::class, 'success_stories'])->name('success-stories');
Route::get('contact-us', [ContactController::class, 'show'])->name('contact-us');
Route::post('contact-us', [ContactController::class, 'store'])->middleware('throttle:5,1')->name('contact-us.submit');
Route::get('privacy-policy', [WelcomeController::class, 'privacy_policy'])->name('privacy-policy');
Route::get('refund-policy', [WelcomeController::class, 'refund_policy'])->name('refund-policy');
Route::redirect('refun-policy', '/refund-policy')->name('refun-policy');
Route::get('terms-and-conditions', [WelcomeController::class, 'terms_and_conditions'])->name('terms-and-conditions');
Route::get('child-safety-standard', [WelcomeController::class, 'child_safety'])->name('child-safety-standard');
Route::get('pricing', [WelcomeController::class, 'pricing'])->name('pricing');
Route::get('blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('faqs', [WelcomeController::class, 'faqs'])->name('faqs');
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('profile-preview/{profileId}', [\App\Website\Http\Controllers\ProfileShareController::class, 'show'])
    ->name('profile.share-preview');

// Preserve existing policy links while using the public pages everywhere.
Route::redirect('members/terms-and-conditions', '/terms-and-conditions')->name('member.terms-and-conditions');
Route::redirect('member/privacy-policy', '/privacy-policy')->name('member.privacy-policy');
