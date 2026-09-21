# Combined website, admin, and API

`himrishtey-one` now contains the public websites, member area, admin panel, and mobile API. The original `../himrishtey` project remains unchanged.

## Local URLs

| Site | Website |
| --- | --- |
| Him Rishtey | http://himrishtey-one.ddev.site or http://himrishtey-combined.ddev.site |
| Dogri Rishtey | http://dogririshtey-combined.ddev.site |
| Gall Pakki | http://gallpakki-combined.ddev.site |
| Dev Bhoomi | http://devbhoomi-combined.ddev.site |

Admin: `/admin/login`. API: `/api/v1`, with the existing `X-App-Code` header. These preview aliases avoid taking over domains used by the original DDEV project.

For a fresh DDEV checkout, add the four `*-combined` aliases to `additional_hostnames` in `.ddev/config.yaml`, then run `ddev restart`. DDEV configuration and `.env` are local, ignored files.

## Database routing

The Laravel default connection remains the central database containing admins, roles, permissions, sites, and applications. Do not change `DB_DATABASE` to a member database.

- Website hosts resolve through `config/site.php`; each branding entry maps to a central `sites.code` (`main`, `dogririshtey`, `gallpakki`, or `devbhoomi`). Database credentials come from the existing `sites` records.
- Admin requests retain the selected-site middleware and admin guard.
- API requests retain application-code resolution and Sanctum authentication.
- Website models and raw queries explicitly use the `site` connection. The website uses the existing legacy permissive SQL mode; admin/API retain their existing behavior.
- Member cookies have distinct names per site and are host-only. The website has a separate `member` guard and remember-token provider.

The website is under `app/Website` to preserve its model behavior separately from the admin/API models. Website routes are in `routes/website`. Both parts use a single Composer installation, Vite build, application key, and Laravel runtime.

## Install and migrations

```sh
composer install
npm ci --ignore-scripts
npm run build
php artisan migrate
php artisan sites:migrate --force
php artisan storage:link
```

`sites:migrate` runs only `database/migrations/site` on each configured site. Limit it with repeated `--site=main` / `--site=dogririshtey` options. It prepares contact messages/read status, member remember tokens, blog posts, and push subscriptions. Existing tables and records are preserved. Content-table rollback intentionally retains existing tables.

Use the normal central migration workflow for central schema changes. Do not run the central migration directory against a site database.

## Shared content and files

Website and API contact submissions go directly into the selected site's `contact_messages`. Super admins read the same rows; the sidebar red dot reflects unread messages for the selected site.

Website profile image URLs support both admin `storage/members/...` paths and the website's legacy `photos/photo` files. Existing local website assets and uploads were copied. If an old profile image is absent locally, the website retains its production-origin fallback. Copy the complete production upload directories when deploying the combined application.

New website success-story uploads use `storage/app/public/success-stories`, matching admin uploads. Existing `public/uploads/success-stories` files remain readable. Member edits require ownership and return the story to moderation.

## Configuration and deployment

The local `.env` retains existing admin/database settings and adds missing website branding/integration values. `config/site.php` supplies branding defaults. `config/website.php` holds cache-compatible website SMS, payment, and push settings. Existing admin/API integration configuration remains in `config/services.php`.

For a production deployment:

1. Configure the central `sites` and `applications` records to point to the intended four databases.
2. Point the four website domains and admin hostname at this application's `public` directory, with their TLS certificates.
3. Preserve production upload files and configure the storage link.
4. Set the website branding, mail, Razorpay, Nimbus, Google, and push credentials in the deployment environment. Payment and messaging settings are retained from the existing applications; live external transactions were not exercised during integration.
5. Register each domain's `/google-signup-callback` URL with Google. The callback is selected from the current website host.
6. Run the central and site migrations, build assets, and cache configuration/routes/views as appropriate.

Keep the combined application's key stable. Existing website sessions from the other application's key are not migrated; users should sign in again after cutover. The source website and live DNS were not changed by the integration.

## Verification

```sh
php artisan test --compact
node --test tests/js/*.test.mjs tests/Unit/*.test.mjs
npm run build
php artisan view:cache
```

Integration tests cover all four host mappings, central/site separation, member authentication, website/API-to-admin contact messages, member story ownership/storage, homepage profile selection, and photo upload approval. Local DDEV checks also render public and member pages against all four databases with outgoing mail/HTTP blocked for member checks and database changes rolled back.


## Website update sync (2026-09-15)

Ported the source website changes from `2bf605c` through `e6c1559`: app-download
layout and QR images, store links, Dev Bhoomi branding, footer icon, CSS variables,
password handler updates, and unused website-file cleanup. Combined database routing,
member sessions, shared photo storage, and admin/API code are retained.
Dev Bhoomi's canonical hostname is now `devbhoomirishtey.com`; the earlier spelling
and combined DDEV aliases remain supported. Environment overrides still take
precedence over branding defaults.

### Member passwords

Member passwords currently use plaintext storage and comparison across website
and API login, registration, password reset/change, and admin member creation.
API login does not automatically hash passwords. The member password hashing
command has been removed; no password migration is required for plaintext rows.
Admin/staff account passwords, OTPs, and remember tokens retain their existing
hashing behavior. Existing password hashes cannot be converted back to plaintext;
affected members need to reset their passwords. No database records were changed
as part of this code update.

New member profile and gallery uploads (website, admin, and API) are stored directly in `public/photos/photo` using the `profile_photos` disk. Database records contain the generated filename. Admin-generated WebP variants use the `large`, `medium`, and `thumb` subdirectories. Existing files are not moved; legacy storage and gallery URLs remain supported. Success stories, blog images, and documents retain their separate storage locations.
