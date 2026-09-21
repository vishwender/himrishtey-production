<?php

namespace App\Website\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InstagramFeedService
{
    public function getLatestMedia(int $limit = 6): Collection
    {
        $instagram = config('site.current.instagram', []);
        $accessToken = $instagram['access_token'] ?? null;

        if (! ($instagram['enabled'] ?? false) || empty($accessToken) || $limit < 1) {
            return collect();
        }

        $key = $this->cacheKey();
        $cached = Cache::get($key);
        // Older entries contain Collections that restricted unserialization rejects.
        if (! is_array($cached)) {
            Cache::forget($key);
            $cached = [];
        }

        if (! isset($cached[$limit]) || ! is_array($cached[$limit])) {
            $cached[$limit] = $this->fetchMedia($accessToken, $limit);
            Cache::put($key, $cached, now()->addMinutes((int) config('instagram.cache_minutes', 60)));
        }

        return collect($cached[$limit]);
    }

    private function fetchMedia(string $accessToken, int $limit): array
    {
        try {
            $response = Http::timeout(15)->retry(2, 500)->get(
                'https://graph.instagram.com/me/media',
                [
                    'fields' => 'id,caption,media_type,media_product_type,media_url,thumbnail_url,permalink,timestamp',
                    'limit' => $limit,
                    'access_token' => $accessToken,
                ]
            );

            if ($response->failed()) {
                Log::warning('Instagram API request failed', ['status' => $response->status()]);

                return [];
            }

            $data = $response->json('data', []);

            return is_array($data) ? array_values(array_filter($data, 'is_array')) : [];
        } catch (\Throwable $exception) {
            // HTTP exception messages can contain the access token in the URL.
            Log::warning('Instagram feed request failed', ['exception' => $exception::class]);

            return [];
        }
    }

    public function clearCache(): void
    {
        Cache::forget($this->cacheKey());
    }

    private function cacheKey(): string
    {
        return 'instagram_feed_'.md5(config('site.current.key') ?? request()->getHost());
    }
}
