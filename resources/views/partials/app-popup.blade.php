     <div class="app-download-popup" id="appDownloadPopup" aria-hidden="true">
         <div class="app-download-popup__backdrop" data-app-popup-close></div>

         <div class="app-download-popup__card" role="dialog" aria-modal="true" aria-labelledby="appPopupTitle">

             <button
                 type="button"
                 class="app-download-popup__close"
                 data-app-popup-close
                 aria-label="Close">
                 &times;
             </button>

             <div class="app-download-popup__icon">
                 <i data-lucide="heart"></i>
             </div>

             <span class="app-download-popup__eyebrow">
                 Find your match anywhere
             </span>

             <h2 id="appPopupTitle">
                 Find your perfect match<br>
                 on the <strong>{{ $siteName }} App</strong>
             </h2>

             <p>
                 Discover new profiles, connect with matches and continue
                 your partner search anytime, wherever you are.
             </p>

             <div class="app-download-popup__buttons">

                 {{-- Google Play --}}
                 @if(!empty(config('site.current.android_app_url')))
                 <a
                     href="{{ config('site.current.android_app_url') }}"
                     target="_blank"
                     rel="noopener"
                     class="app-download-popup__store">

                     <i data-lucide="smartphone"></i>

                     <span>
                         <small>GET IT ON</small>
                         <b>Google Play</b>
                     </span>
                 </a>
                 @endif


                 {{-- App Store --}}
                 @if(!empty(config('site.current.ios_app_url')))
                 <a
                     href="{{ config('site.current.ios_app_url') }}"
                     target="_blank"
                     rel="noopener"
                     class="app-download-popup__store">

                     <i data-lucide="apple"></i>

                     <span>
                         <small>DOWNLOAD ON THE</small>
                         <b>App Store</b>
                     </span>
                 </a>
                 @endif

             </div>

             <button
                 type="button"
                 class="app-download-popup__later"
                 data-app-popup-close>
                 Maybe later
             </button>

         </div>
     </div>