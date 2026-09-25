<!DOCTYPE html>
<html lang="en" prefix="og: https://ogp.me/ns#">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ $name }} – Profile</title>
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ $name }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:image" content="{{ $photo }}">
    <meta property="og:image:alt" content="{{ $name }} profile photo">
    <meta property="og:url" content="{{ $previewUrl }}">
    <link rel="stylesheet" href="{{ asset('assets/css/profile-share-preview.css') }}?v={{ filemtime(public_path('assets/css/profile-share-preview.css')) }}">
</head>

<body>
    <div class="share-shell">
        <header class="share-header">
            <a class="share-brand" href="{{ url('/') }}">{{ $siteName ?? config('app.name') }}</a>
            <span>Profile introduction</span>
        </header>
        <main>
            <section class="share-hero" aria-labelledby="profileName">
                <div class="share-photo"><img src="{{ $photo }}" alt="{{ $name }} profile photo"></div>
                <div class="share-intro">
                    <span class="share-eyebrow">A meaningful connection starts here</span>
                    <h1 id="profileName">{{ $name }}</h1>
                    <span class="share-id">{{ $profileId }}</span>
                    <p>{{ $description }}</p>
                    <div class="share-facts">
                        <span>{{ $sections['Personal details']['Age'] }} years</span>
                        <span>{{ $sections['Personal details']['Height'] }}</span>
                        <span>{{ $sections['Personal details']['Marital status'] }}</span>
                    </div>
                    <div class="share-hero-actions">
                        <a class="share-button" href="{{ $profileUrl }}">View full profile</a>
                        <a class="share-button share-button-secondary" href="https://wa.me/?text={{ rawurlencode($shareText) }}" target="_blank" rel="noopener noreferrer">Share via WhatsApp</a>
                    </div>
                </div>
            </section>
            <div class="share-layout">
                <aside class="share-nav" aria-label="Profile sections">
                    <p>Get to know {{ $name }}</p>
                    @foreach($sections as $heading => $fields)
                        <a href="#section-{{ $loop->index }}">{{ $heading }} <span aria-hidden="true">↗</span></a>
                    @endforeach
                </aside>
                <div class="share-sections">
                    @foreach($sections as $heading => $fields)
                        <section class="share-card" id="section-{{ $loop->index }}" aria-labelledby="heading-{{ $loop->index }}">
                            <h2 id="heading-{{ $loop->index }}"><span class="share-section-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>{{ $heading }}</h2>
                            @if($heading === 'About me')
                                <p class="share-about">{{ $fields['About'] }}</p>
                            @else
                                <dl class="share-details">
                                    @foreach($fields as $label => $value)
                                        <div><dt>{{ $label }}</dt><dd>{{ $value }}</dd></div>
                                    @endforeach
                                </dl>
                            @endif
                        </section>
                    @endforeach
                </div>
            </div>
        </main>
        <footer class="share-footer">{{ $siteName ?? config('app.name') }} · Bringing people together</footer>
    </div>
</body>
</html>
