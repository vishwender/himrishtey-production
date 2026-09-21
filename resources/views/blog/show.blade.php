@extends('layouts.app')

@section('title', ($post->meta_title ?: $post->title) . ' - ' . $siteName)
@section('description', $post->meta_description ?: \Illuminate\Support\Str::limit(strip_tags($post->excerpt ?: $post->content), 155))

@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/blog.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/public-pages-v2.css') }}">
@endsection

@section('content')
<main class="blog-page article-page">
  <article>
    <header class="article-header">
      <div class="container-xl article-header-inner">
        <a class="article-back" href="{{ route('blog.index') }}"><i data-lucide="arrow-left" width="16" height="16"></i> All articles</a>
        <span class="blog-kicker">{{ $siteName }} Journal</span>
        <h1>{{ $post->title }}</h1>
        <time datetime="{{ $post->publication_date->toDateString() }}">Published {{ $post->publication_date->format('F j, Y') }}</time>
      </div>
    </header>

    <div class="container-xl article-shell">
      @if ($post->featured_image)
        <figure class="article-featured-image">
          <img src="{{ asset('storage/blog/' . $post->featured_image) }}" alt="{{ $post->title }}">
        </figure>
      @endif

      @if ($post->excerpt)
        <p class="article-lead">{{ strip_tags($post->excerpt) }}</p>
      @endif

      <div class="article-content">{!! $post->content !!}</div>
    </div>
  </article>

  @if ($relatedPosts->isNotEmpty())
    <aside class="container-xl related-articles" aria-labelledby="related-heading">
      <span class="blog-kicker">Keep reading</span>
      <h2 id="related-heading">More from {{ $siteName }}</h2>
      <div class="related-grid">
        @foreach ($relatedPosts as $relatedPost)
          <a class="related-card" href="{{ route('blog.show', $relatedPost->slug) }}">
            <time datetime="{{ $relatedPost->publication_date->toDateString() }}">{{ $relatedPost->publication_date->format('M j, Y') }}</time>
            <h3>{{ $relatedPost->title }}</h3>
            <span>Read article <i data-lucide="arrow-right" width="15" height="15"></i></span>
          </a>
        @endforeach
      </div>
    </aside>
  @endif
</main>
@endsection
