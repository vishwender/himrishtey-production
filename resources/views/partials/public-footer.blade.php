<footer class="public-footer">

    <div class="wrap">

        <div class="public-footer-grid">

            {{-- Brand --}}
            <div class="footer-brand">

                <a
                    class="public-logo footer-logo"
                    href="{{ route('welcome') }}"
                    aria-label="{{ $siteName }} home">
                    <img
                        src="{{ asset($siteLogo) }}"
                        alt="{{ $siteName }}">
                </a>

                <p>
                    {{ $siteFooterText }}
                </p>

                @php
                $socialLinks = [
                'Facebook' => [
                'url' => $siteSocial['facebook'] ?? null,
                'icon' => '
                <path d="M14 8h-2V6c0-.6.4-1 1-1h1V2h-2c-2.2 0-4 1.8-4 4v2H6v3h2v7h3v-7h2l1-3Z" />',
                ],
                'X' => [
                'url' => $siteSocial['x'] ?? null,
                'icon' => '
                <path fill="currentColor" stroke="none" d="M18.901 1.153h3.68l-8.04 9.19L24 22.846h-7.406l-5.8-7.584-6.64 7.584H.47l8.6-9.835L0 1.154h7.594l5.243 6.932 6.064-6.933Zm-1.29 19.49h2.039L6.487 3.24H4.3l13.31 17.403Z" />',
                ],
                'Pinterest' => [
                'url' => $siteSocial['pinterest'] ?? null,
                'icon' => '<path d="M12 0a12 12 0 0 0-4.37 23.18c-.03-.94-.01-2.07.24-3.15l1.54-6.51s-.39-.78-.39-1.93c0-1.81 1.05-3.16 2.36-3.16 1.11 0 1.65.84 1.65 1.84 0 1.12-.71 2.79-1.08 4.34-.31 1.3.66 2.36 1.94 2.36 2.33 0 4.12-2.46 4.12-6.02 0-3.15-2.27-5.35-5.51-5.35-3.75 0-5.95 2.81-5.95 5.71 0 1.13.43 2.34.98 3 .11.13.13.25.09.38l-.37 1.49c-.06.24-.2.29-.46.18-1.7-.79-2.77-3.27-2.77-5.26 0-4.28 3.11-8.21 8.96-8.21 4.7 0 8.35 3.35 8.35 7.83 0 4.67-2.94 8.43-7.02 8.43-1.37 0-2.66-.71-3.1-1.55l-.84 3.21c-.3 1.17-1.12 2.64-1.67 3.53A12 12 0 1 0 12 0Z" />',
                ],
                'Instagram' => [
                'url' => $siteSocial['instagram'] ?? null,
                'icon' => '
                <rect width="17" height="17" x="3.5" y="3.5" rx="4" />
                <circle cx="12" cy="12" r="4" />
                <circle cx="17.5" cy="6.5" r="1" />',
                ],
                'YouTube' => [
                'url' => $siteSocial['youtube'] ?? null,
                'icon' => '
                <path d="M20.5 7.2a2.5 2.5 0 0 0-1.8-1.8C17.1 5 12 5 12 5s-5.1 0-6.7.4a2.5 2.5 0 0 0-1.8 1.8A26 26 0 0 0 3 12a26 26 0 0 0 .5 4.8 2.5 2.5 0 0 0 1.8 1.8C6.9 19 12 19 12 19s5.1 0 6.7-.4a2.5 2.5 0 0 0 1.8-1.8A26 26 0 0 0 21 12a26 26 0 0 0-.5-4.8Z" />
                <path d="m10 15 5-3-5-3v6Z" />',
                ],
                ];
                @endphp

                @if (collect($socialLinks)->pluck('url')->filter()->isNotEmpty())
                <nav class="footer-social" aria-label="Social media">
                    @foreach ($socialLinks as $label => $socialLink)
                    @if (filled($socialLink['url']))
                    <a href="{{ $socialLink['url'] }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                        <svg class="social-{{ strtolower($label) }}" viewBox="0 0 24 24" aria-hidden="true" focusable="false">{!! $socialLink['icon'] !!}</svg>
                    </a>
                    @endif
                    @endforeach
                </nav>
                @endif

            </div>


            {{-- Quick Links --}}
            <div class="footer-column">

                <b>Quick Links</b>

                <a href="{{ route('welcome') }}#search">
                    Search Matches
                </a>

                <a href="{{ route('welcome') }}#how">
                    How It Works
                </a>

                <a href="{{ route('pricing') }}">
                    Membership Plans
                </a>

                <a href="{{ route('success-stories') }}">
                    Success Stories
                </a>

            </div>


            {{-- Company --}}
            <div class="footer-column">

                <b>Explore</b>

                <a href="{{ route('about-us') }}">
                    About Us
                </a>

                <a href="{{ route('blog.index') }}">
                    Blog
                </a>

                <a href="{{ route('faqs') }}">
                    FAQs
                </a>

                <a href="{{ route('contact-us') }}">
                    Contact Us
                </a>

            </div>


            {{-- Legal --}}
            <div class="footer-column">

                <b>Legal &amp; Safety</b>

                <a href="{{ route('terms-and-conditions') }}">
                    Terms &amp; Conditions
                </a>

                <a href="{{ route('privacy-policy') }}">
                    Privacy Policy
                </a>

                <a href="{{ route('refund-policy') }}">
                    Refund Policy
                </a>

                <a href="{{ route('child-safety-standard') }}">
                    Child Safety
                </a>

            </div>

        </div>


        {{-- Copyright --}}
        <small class="copyright">
            &copy; {{ date('Y') }} {{ $siteName }}.
            All rights reserved.
        </small>

    </div>

</footer>
