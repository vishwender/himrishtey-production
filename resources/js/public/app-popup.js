         document.addEventListener('DOMContentLoaded', function() {

             const popup = document.getElementById('appDownloadPopup');

             if (!popup) {
                 return;
             }

             const storageKey = 'app-download-popup-closed';

             /*
              * Don't show again during the same browser session
              */
             if (sessionStorage.getItem(storageKey)) {
                 return;
             }

             /*
              * Show 3 seconds after page loads
              */
             setTimeout(function() {

                 popup.classList.add('is-visible');
                 popup.setAttribute('aria-hidden', 'false');

             }, 3000);


             /*
              * Close buttons + backdrop
              */
             popup.querySelectorAll('[data-app-popup-close]')
                 .forEach(function(button) {

                     button.addEventListener('click', function() {

                         popup.classList.remove('is-visible');
                         popup.setAttribute('aria-hidden', 'true');

                         sessionStorage.setItem(storageKey, '1');

                     });

                 });


             /*
              * ESC key
              */
             document.addEventListener('keydown', function(event) {

                 if (
                     event.key === 'Escape' &&
                     popup.classList.contains('is-visible')
                 ) {
                     popup.classList.remove('is-visible');
                     popup.setAttribute('aria-hidden', 'true');

                     sessionStorage.setItem(storageKey, '1');
                 }

             });

         });