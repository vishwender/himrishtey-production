<?php

namespace Tests\Feature;

use App\Services\SiteDatabaseService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('sites', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->boolean('status');
        });

        foreach (config('site.sites') as $site) {
            DB::table('sites')->insert(['code' => $site['code'], 'status' => true]);
        }

        $this->mock(SiteDatabaseService::class)->shouldReceive('connect')->andReturnNull();
    }

    public function test_sitemap_matches_the_requested_page_list_as_valid_xml(): void
    {
        $response = $this->get('https://www.gallpakki.com/sitemap.xml')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
        $xml->registerXPathNamespace('s', 'http://www.sitemaps.org/schemas/sitemap/0.9');
        $locations = array_map(fn ($loc) => (string) $loc, $xml->xpath('//s:loc'));

        $paths = [
            '/', '/about-us', '/login', '/success-stories', '/pricing',
            '/contact-us', '/faqs', '/privacy-policy', '/terms-and-conditions',
            '/refund-policy', '/child-safety-standard', '/forgot-password',
        ];
        $this->assertSame(
            array_map(fn ($path) => 'https://gallpakki.com'.$path, $paths),
            $locations,
        );
        $this->assertCount(0, $xml->xpath('//s:lastmod'));
    }

    public function test_each_site_uses_its_own_canonical_domain_and_robots_link(): void
    {
        foreach (config('site.sites') as $host => $site) {
            $baseUrl = rtrim($site['app_url'], '/');
            $this->get('https://'.$host.'/sitemap.xml')
                ->assertOk()
                ->assertSee('<loc>'.$baseUrl.'/</loc>', false);

            $this->get('https://'.$host.'/robots.txt')
                ->assertOk()
                ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
                ->assertContent("User-agent: *\nDisallow:\n\nSitemap: {$baseUrl}/sitemap.xml\n");
        }

        $this->assertFileDoesNotExist(public_path('robots.txt'));
    }

    public function test_unknown_hosts_have_no_website_sitemap(): void
    {
        $this->get('http://localhost/sitemap.xml')->assertNotFound();
        $this->get('http://localhost/robots.txt')->assertOk()
            ->assertContent("User-agent: *\nDisallow:\n");
    }
}
