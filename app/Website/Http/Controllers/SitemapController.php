<?php

namespace App\Website\Http\Controllers;

use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $baseUrl = $this->baseUrl();

        $routes = [
            'welcome',
            'about-us',
            'success-stories',
            'pricing',
            'contact-us',
            'faqs',
            'privacy-policy',
            'terms-and-conditions',
            'refund-policy',
            'child-safety-standard',
        ];

        $urls = [];

        foreach ($routes as $route) {
            $urls[] = [
                'loc' => $baseUrl . route($route, [], false),
            ];
        }

        $xml = ltrim(
            view('sitemap', compact('urls'))->render()
        );

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    public function robots(): Response
    {
        $content = "User-agent: *\nDisallow:\n";

        if (config('site.current')) {
            $content .= "\nSitemap: "
                . $this->baseUrl()
                . "/sitemap.xml\n";
        }

        return response($content, 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    private function baseUrl(): string
    {
        $baseUrl = rtrim(
            (string) config('site.current.app_url'),
            '/'
        );

        // Dev Bhoomi Rishtey uses WWW as its canonical domain.
        if (
            parse_url($baseUrl, PHP_URL_HOST)
            === 'devbhoomirishtey.com'
        ) {
            return 'https://www.devbhoomirishtey.com';
        }

        return $baseUrl;
    }
}
