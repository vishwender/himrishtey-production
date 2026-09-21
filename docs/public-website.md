# Public website code

Public routes live in `routes/website/public.php`. Site selection and database routing remain in `SiteContext` and the existing website middleware.

- `WelcomeController` renders landing, information, policy, and pricing pages. Laravel injects its homepage services.
- `ContactController` creates the captcha, saves inquiries, and sends the notification. `ContactRequest` owns validation. Mail failures do not discard a saved inquiry.
- `PublicPageData` owns homepage summary and membership-plan queries and caching.
- `HomepageProfiles` and `HomepageCities` own homepage profile and location selection.
- `InstagramFeedService` owns remote fetching, cache recovery, and response normalization. The controller supplies its results to the template.
- `WebsiteServiceProvider` supplies branding and metadata from `config/site.php`; individual pages can override metadata.

## Cache format

Keep cached public data as arrays and scalar values. `cache.serializable_classes` is intentionally false; cached Collections and Eloquent models cannot be restored reliably. Create view objects or Collections after reading the cache.

Summary and pricing cache keys use `:v2` to bypass older serialized models. Instagram detects and replaces old object entries automatically. Its cache separates requested item limits and sites; `clearCache()` removes all limits for the current site. No global cache flush is required.

## Verification

```sh
php artisan test --compact tests/Feature/PublicPageRefactorTest.php tests/Feature/InstagramFeedServiceTest.php tests/Feature/SiteMetadataTest.php tests/Feature/SitemapTest.php
php artisan view:cache
```

These tests cover contact validation, cached page data, Instagram recovery/failure/isolation, metadata, and sitemap output. They do not exercise live Instagram requests or mail delivery.
