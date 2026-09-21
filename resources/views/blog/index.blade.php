@extends('layouts.public')

@section('title', 'Blog - ' . $siteName)

@section(
'description',
'Read matrimonial tips, relationship advice and helpful stories from ' . $siteName . '.'
)

@section('main-class', 'public-main blog-main')

@section('content')

<div class="blog-page">

  <section class="blog-hero">
    <div class="wrap">
      <span class="editorial-kicker">Stories & Advice</span>

      <h1>
        Guidance for a more meaningful
        <em>matrimonial journey.</em>
      </h1>

      <p>
        Thoughtful advice, relationship insights and practical guidance
        to help you navigate your search with confidence.
      </p>
    </div>
  </section>


  <section class="blog-content">
    <div class="wrap">

      @if($posts->count())

      <div class="blog-grid">

        @foreach($posts as $post)

        <article class="blog-card">

          @if(!empty($post->image))
          <a
            class="blog-card-image"
            href="{{ route('blog.show', $post->slug) }}">
            <img
              src="{{ asset($post->image) }}"
              alt="{{ $post->title }}"
              loading="lazy">
          </a>
          @endif

          <div class="blog-card-body">

            @if(!empty($post->published_at))
            <time
              class="blog-date"
              datetime="{{ $post->published_at }}">
              {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }}
            </time>
            @endif

            <h2>
              <a href="{{ route('blog.show', $post->slug) }}">
                {{ $post->title }}
              </a>
            </h2>

            @if(!empty($post->excerpt))
            <p>
              {{ $post->excerpt }}
            </p>
            @elseif(!empty($post->content))
            <p>
              {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}
            </p>
            @endif

            <a
              class="blog-read-more"
              href="{{ route('blog.show', $post->slug) }}">
              Read article
              <span aria-hidden="true">→</span>
            </a>

          </div>

        </article>

        @endforeach

      </div>


      @if(method_exists($posts, 'links'))
      <div class="blog-pagination">
        {{ $posts->links() }}
      </div>
      @endif

      @else

      <div class="blog-empty">
        <i data-lucide="book-open"></i>

        <h2>No articles yet</h2>

        <p>
          New stories and matrimonial guidance will appear here soon.
        </p>
      </div>

      @endif

    </div>
  </section>

</div>

@endsection