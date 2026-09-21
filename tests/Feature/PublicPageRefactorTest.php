<?php

namespace Tests\Feature;

use App\Website\Http\Requests\ContactRequest;
use App\Website\Models\ContactMessage;
use App\Website\Services\PublicPageData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PublicPageRefactorTest extends TestCase
{
    public function test_cached_arrays_preserve_public_page_view_data(): void
    {
        config(['site.current.key' => 'gallpakki.com']);
        Cache::put('gallpakki.com:public-home-summary:v2', [
            'maleProfile' => null,
            'femaleProfile' => ['full_name' => 'Example', 'birth_date_time' => '1990-01-01', 'city_living_in' => 'Amritsar'],
            'totalprofiles' => 12,
        ]);
        Cache::put('gallpakki.com:public-membership-plans:v2', [
            ['id' => 1, 'plan_name' => 'Silver', 'final_cost' => 100],
        ]);
        $service = app(PublicPageData::class);
        $this->assertSame('Example', $service->homeSummary()['femaleProfile']->full_name);
        $this->assertNull($service->homeSummary()['maleProfile']);
        $this->assertSame(12, $service->homeSummary()['totalprofiles']);
        $this->assertSame('Silver', $service->membershipPlans()->first()->plan_name);
        $this->assertSame(100, $service->membershipPlans()->first()->final_cost);
    }

    public function test_contact_validation_preserves_captcha_and_honeypot_checks(): void
    {
        $request = ContactRequest::create('/contact-us', 'POST');
        $request->setLaravelSession(app('session.store'));
        $request->session()->put('contact_captcha', ['answer' => 5, 'expires_at' => now()->addMinutes(30)->timestamp]);
        $data = [
            'name' => 'Example', 'email' => 'example@example.com',
            'subject' => ContactMessage::SUBJECTS[0],
            'message' => 'Please help with my membership.', 'captcha' => 5,
        ];
        $validate = fn (array $input) => Validator::make($input, $request->rules(), $request->messages());
        $this->assertTrue($validate($data)->passes());
        $this->assertTrue($validate(array_replace($data, ['captcha' => 6]))->errors()->has('captcha'));
        $this->assertTrue($validate($data + ['website' => 'spam'])->errors()->has('website'));
        $request->session()->put('contact_captcha.expires_at', now()->subMinute()->timestamp);
        $this->assertTrue($validate($data)->errors()->has('captcha'));
    }
}
