<?php

namespace Tests\Feature;

use App\Services\SiteDatabaseService;
use App\Website\Services\HomepageCities;
use App\Website\Services\HomepageProfiles;
use App\Website\Services\InstagramFeedService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SiteMetadataTest extends TestCase
{
    private const DESCRIPTION = 'Find genuine Punjabi matrimonial profiles with Gall Pakki. Explore Punjabi brides and grooms in Punjab and discover trusted matchmaking services for your perfect life partner.';

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        Schema::create('sites', function (Blueprint $table): void {
            $table->id();
            $table->string('code');
            $table->boolean('status');
        });

        foreach (config('site.sites') as $host => $site) {
            DB::table('sites')->insert(['code' => $site['code'], 'status' => true]);
            Cache::put($host.':public-home-summary:v2', ['totalprofiles' => 0]);
            Cache::put($host.':public-membership-plans:v2', []);
        }

        $this->mock(SiteDatabaseService::class)->shouldReceive('connect')->andReturnNull();
        $this->mock(HomepageProfiles::class)->shouldReceive('get')->andReturn(collect());
        $this->mock(HomepageCities::class)->shouldReceive('get')->andReturn(collect());
        $this->mock(InstagramFeedService::class)->shouldReceive('getLatestMedia')->andReturn(collect());
    }

    public function test_gall_pakki_homepage_outputs_the_exact_metadata_for_both_hosts(): void
    {
        foreach (['gallpakki.com', 'www.gallpakki.com'] as $host) {
            $this->get('https://'.$host.'/')->assertOk()
                ->assertSee('<title>Gall Pakki | Punjabi Matrimony &amp; Marriage Bureau in Punjab</title>', false)
                ->assertSee('<meta name="description" content="'.self::DESCRIPTION.'">', false)
                ->assertDontSee('Meaningful connections. Genuine profiles. A simple way to find your life partner.');
        }
    }

    public function test_shared_layout_uses_site_description_and_preserves_page_title(): void
    {
        $this->get('https://gallpakki.com/pricing')->assertOk()
            ->assertSee('<meta name="description" content="'.self::DESCRIPTION.'">', false)
            ->assertSee('<title>Membership Plans - Gallpakki</title>', false);
    }

    public function test_analytics_ids_follow_the_current_site_and_blank_ids_disable_tracking(): void
    {
        $sites = config('site.sites');
        foreach ($sites as &$site) {
            $site['google_analytics_id'] = null;
            $site['google_tag_manager_id'] = null;
        }
        unset($site);
        $sites['gallpakki.com']['google_analytics_id'] = 'G-GALLPAKKI1';
        $sites['gallpakki.com']['google_tag_manager_id'] = 'GTM-GALL123';
        $sites['himrishtey.com']['google_analytics_id'] = 'G-HIMRISHTE1';
        config(['site.sites' => $sites]);

        foreach (['/', '/pricing'] as $path) {
            $this->get('https://gallpakki.com'.$path)->assertOk()
                ->assertSee('gtag/js?id=G-GALLPAKKI1', false)
                ->assertSee("gtag('config', 'G-GALLPAKKI1')", false)
                ->assertDontSee('G-HIMRISHTE1');
            $this->get('https://himrishtey.com'.$path)->assertOk()
                ->assertSee('gtag/js?id=G-HIMRISHTE1', false)
                ->assertDontSee('G-GALLPAKKI1')
                ->assertDontSee('GTM-GALL123');
            $this->get('https://dogririshtey.com'.$path)->assertOk()
                ->assertDontSee('googletagmanager.com');
        }

        $this->get('https://www.gallpakki.com/')->assertOk()
            ->assertSee('G-GALLPAKKI1')->assertSee('GTM-GALL123');
    }

    public function test_metadata_does_not_leak_between_sites(): void
    {
        $this->get('https://gallpakki.com/')->assertOk()->assertSee(self::DESCRIPTION);
        $this->get('https://himrishtey.com/')->assertOk()
            ->assertSee('Find genuine matrimonial profiles with HimRishtey.')
            ->assertDontSee(self::DESCRIPTION);
    }
}
