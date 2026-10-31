<?php

namespace App\Support;

use Illuminate\Http\Request;

class RobotsPolicy
{
    public static function forRequest(Request $request): string
    {
        $publicPage = $request->isMethod('GET') || $request->isMethod('HEAD');
        $publicPage = $publicPage && $request->routeIs(
            'welcome', 'about-us', 'success-stories', 'contact-us',
            'privacy-policy', 'refund-policy', 'terms-and-conditions',
            'child-safety-standard', 'pricing', 'blog.index', 'blog.show', 'faqs',
            'sitemap', 'robots',
        );

        return $publicPage ? 'index, follow' : 'noindex, nofollow';
    }
}
