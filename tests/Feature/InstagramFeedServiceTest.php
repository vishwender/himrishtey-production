<?php

namespace Tests\Feature;

use App\Website\Services\InstagramFeedService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class InstagramFeedServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        config(['site.current' => [
            'key' => 'gallpakki.com',
            'instagram' => ['enabled' => true, 'access_token' => 'test-token'],
        ]]);
        Http::preventStrayRequests();
    }

    public function test_incomplete_cached_objects_are_replaced_with_plain_arrays(): void
    {
        $key = 'instagram_feed_'.md5('gallpakki.com');
        $broken = unserialize(serialize(collect([['id' => 'old']])), ['allowed_classes' => false]);
        Cache::put($key, $broken, 60);
        Http::fake(['graph.instagram.com/*' => Http::response(['data' => [['id' => 'new']]])]);

        $service = app(InstagramFeedService::class);
        $this->assertSame([['id' => 'new']], $service->getLatestMedia()->all());
        $this->assertSame([['id' => 'new']], $service->getLatestMedia()->all());
        $this->assertIsArray(Cache::get($key));
        $this->assertSame(Cache::get($key), unserialize(serialize(Cache::get($key)), ['allowed_classes' => false]));
        Http::assertSentCount(1);
        $service->clearCache();
        $this->assertNull(Cache::get($key));
    }

    public function test_sites_and_requested_limits_do_not_share_results(): void
    {
        Http::fake(['graph.instagram.com/*' => Http::response(['data' => [['id' => 'one']]])]);
        $service = app(InstagramFeedService::class);
        $service->getLatestMedia(1);
        $service->getLatestMedia(6);
        config(['site.current.key' => 'himrishtey.com']);
        $service->getLatestMedia(1);
        Http::assertSentCount(3);
    }

    public function test_disabled_feed_and_api_failure_return_empty_collections(): void
    {
        config(['site.current.instagram.enabled' => false]);
        $service = app(InstagramFeedService::class);
        $this->assertTrue($service->getLatestMedia()->isEmpty());
        Http::assertNothingSent();
        config(['site.current.instagram.enabled' => true]);
        Http::fake(['graph.instagram.com/*' => Http::response([], 403)]);
        $this->assertTrue($service->getLatestMedia()->isEmpty());
    }
}
