<?php

namespace App\Website\Http\Controllers;

use App\Website\Models\Page;
use App\Website\Models\SuccessStory;
use App\Website\Services\HomepageCities;
use App\Website\Services\HomepageProfiles;
use App\Website\Services\InstagramFeedService;
use App\Website\Services\PublicPageData;
use Illuminate\View\View;

class WelcomeController extends Controller
{
    public function __construct(
        private readonly PublicPageData $pageData,
        private readonly HomepageProfiles $profiles,
        private readonly HomepageCities $cities,
        private readonly InstagramFeedService $instagram,
    ) {}

    public function index(): View
    {
        return view('pages.home', [
            'instagramItems' => $this->instagram->getLatestMedia(),
            'data' => $this->pageData->homeSummary(),
            'featuredProfiles' => $this->profiles->get(),
            'searchCities' => $this->cities->get(),
        ]);
    }

    public function designPreview(): View
    {
        return view('pages.design-preview', ['data' => $this->pageData->homeSummary()]);
    }

    public function about(): View
    {
        $aboutUs = Page::where('id', 1)->value('about_us');

        return view('pages.about-us', compact('aboutUs'));
    }

    public function success_stories(): View
    {
        $stories = SuccessStory::where('status', 1)->get();

        return view('pages.success-stories', compact('stories'));
    }

    public function privacy_policy(): View
    {
        $data = Page::where('id', 1)->value('privacy_policy');

        return view('pages.privacy-policy', compact('data'));
    }

    public function refund_policy(): View
    {
        $data = Page::where('id', 1)->value('refund_policy');

        return view('pages.refund-policy', compact('data'));
    }

    public function terms_and_conditions(): View
    {
        $data = Page::where('id', 1)->value('terms_and_conditions');

        return view('pages.terms-and-conditions', compact('data'));
    }

    public function child_safety(): View
    {
        return view('pages.child-safety');
    }

    public function pricing(): View
    {
        $pricings = $this->pageData->membershipPlans();

        return view('pages.pricing', compact('pricings'));
    }

    public function faqs(): View
    {
        return view('pages.faqs');
    }
}
