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
</head>
<body>
    <main style="max-width: 420px; margin: 2rem auto; padding: 1rem; font-family: sans-serif; text-align: center;">
        <img src="{{ $photo }}" alt="{{ $name }}" style="max-width: 100%; max-height: 60vh; border-radius: 12px;">
        <h1>{{ $name }}</h1>
        <p>{{ $description }}</p>
        <a href="{{ $profileUrl }}">View full profile</a>
    </main>
</body>
</html>
