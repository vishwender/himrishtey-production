<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_content_settings', function (Blueprint $table): void {
            $table->id();
            $table->string('site_key', 100)->unique();

            // NULL preserves the site's configuration default; empty text clears it.
            $table->string('logo', 500)->nullable();
            $table->string('tagline', 500)->nullable();
            $table->text('footer_text')->nullable();

            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_title_secondary')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_background', 500)->nullable();
            $table->string('hero_cta_primary', 100)->nullable();
            $table->string('hero_cta_secondary', 100)->nullable();
            $table->boolean('show_hero_stats')->nullable();

            $table->string('why_title')->nullable();
            $table->text('why_text')->nullable();
            $table->string('community_title')->nullable();
            $table->text('community_subtitle')->nullable();

            $table->char('primary_color', 7)->nullable();
            $table->char('secondary_color', 7)->nullable();
            $table->char('accent_color', 7)->nullable();

            $table->string('support_phone', 50)->nullable();
            $table->string('google_analytics_id', 50)->nullable();
            $table->string('google_tag_manager_id', 50)->nullable();
            $table->string('android_app_url', 1000)->nullable();
            $table->string('ios_app_url', 1000)->nullable();

            $table->string('about_intro_title')->nullable();
            $table->string('about_intro_title_secondary')->nullable();

            $table->string('social_facebook', 1000)->nullable();
            $table->string('social_x', 1000)->nullable();
            $table->string('social_instagram', 1000)->nullable();
            $table->string('social_youtube', 1000)->nullable();
            $table->string('social_pinterest', 1000)->nullable();

            $table->boolean('instagram_feed_enabled')->nullable();
            $table->string('instagram_api_base_url', 500)->nullable();
            $table->string('instagram_api_version', 50)->nullable();
            $table->unsignedInteger('instagram_cache_minutes')->nullable();
            $table->unsignedSmallInteger('instagram_feed_limit')->nullable();
            $table->string('instagram_user_id', 100)->nullable();
            // The settings writer must encrypt tokens before persisting them.
            $table->text('instagram_access_token')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_content_settings');
    }
};
