<?php

namespace App\Website\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = rtrim(config('site.current.app_url'), '/');
        $urls = [];

        foreach ([
            'welcome', 'about-us', 'login-form', 'success-stories',
            'pricing', 'contact-us', 'faqs', 'privacy-policy',
            'terms-and-conditions', 'refund-policy', 'child-safety-standard',
            'forgot.password',
        ] as $route) {
            $urls[] = ['loc' => $baseUrl.route($route, [], false)];
        }

        return response()->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nDisallow:\n";

        if (config('site.current')) {
            $content .= "\nSitemap: ".rtrim(config('site.current.app_url'), '/')."/sitemap.xml\n";
        }

        return response($content)->header('Content-Type', 'text/plain; charset=UTF-8');
    }
}
