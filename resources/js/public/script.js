/* ============================================================
   HimRishtey — Homepage Script
   ============================================================ */

(function () {
  'use strict';

  const modal = document.getElementById("rateModal");
  const openBtn = document.getElementById("openRateModal");
  const closeBtn = document.getElementById("closeRateModal");
  const submitBtn = document.querySelector(".rate-submit");
  const ratingContainer = document.getElementById("ratingStars");
  const ratingInput = document.getElementById("ratingValue");
  const stars = document.querySelectorAll("#ratingStars .star");

  if (!modal) return;
  let selectedRating = 0;
  const submitButton = document.querySelector(".rate-submit");

  if (submitButton) {
    submitButton.addEventListener("click", function () {
      const rating = submitButton.dataset.rating || 0;
      const review = document.getElementById("review")?.value?.trim() || "";

      if (rating == 0) {
        HimRishteyToast.error("Please select a rating.");
        return;
      }

      submitButton.disabled = true;
      submitButton.innerText = "Submitting...";

      fetch("/user-rate", {
        method: "POST",
        headers: {
         "Content-Type": "application/json",
         "Accept": "application/json",
         "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || ""
        },
        body: JSON.stringify({
         stars: rating,
         feedback: review
        })
      })
      .then(async response => {
        const data = await response.json();

        if (!response.ok) {
         if (data.errors) {
           throw new Error(Object.values(data.errors).flat().join("\n"));
         }
         throw new Error(data.message || "Something went wrong.");
        }

        return data;
      })
      .then(data => {
        if (data.redirect) {
         modal.classList.remove("show");
         document.getElementById("review").value = "";
         document.getElementById("ratingValue").value = 0;
         submitButton.dataset.rating = 0;

         document.querySelectorAll(".rating-stars .star").forEach(star => {
           star.classList.remove("active", "half");
         });

         window.open(data.url, "_blank");
         return;
        }

        HimRishteyToast.success(data.message);
        document.getElementById("review").value = "";
        document.getElementById("ratingValue").value = 0;
        submitButton.dataset.rating = 0;

        document.querySelectorAll(".rating-stars .star").forEach(star => {
         star.classList.remove("active", "half");
        });

        modal.classList.remove("show");
      })
      .catch(error => {
        HimRishteyToast.error(error.message || 'Unable to submit your feedback.');
        console.error(error);
      })
      .finally(() => {
        submitButton.disabled = false;
        submitButton.innerText = "Submit";
      });
    });
  }

  openBtn?.addEventListener("click", function (e) {
        e.preventDefault();
         
        modal.classList.add("show");
  });

  closeBtn?.addEventListener("click", function () {
        modal.classList.remove("show");
  });

  modal.addEventListener("click", function (e) {
        if (e.target === modal) {
            modal.classList.remove("show");
        }
  });

  function paintStars(rating){

    stars.forEach(star=>{

        const value = Number(star.dataset.value);

        star.classList.remove("full","half");

        if(value <= Math.floor(rating)){
            star.classList.add("full");
        }
        else if(value - 0.5 === rating){
            star.classList.add("half");
        }

    });
  }

 stars.forEach(star=>{
 
   star.addEventListener("mousemove",function(e){
 
       const rect = this.getBoundingClientRect();
 
       const isHalf = (e.clientX - rect.left) < rect.width/2;
 
       const rating = Number(this.dataset.value) - (isHalf ? 0.5 : 0);
 
       paintStars(rating);
 
   });
 
   star.addEventListener("mouseleave",function(){
 
       paintStars(selectedRating);
 
   });
 
   star.addEventListener("click",function(e){
 
       const rect = this.getBoundingClientRect();
 
       const isHalf = (e.clientX - rect.left) < rect.width/2;
 
       selectedRating = Number(this.dataset.value) - (isHalf ? 0.5 : 0);
 
       ratingInput.value = selectedRating;
 
       if (submitButton) {
         submitButton.dataset.rating = selectedRating;
       }
 
       paintStars(selectedRating);
 
   });
 
 });


  /* ---- SIDEBAR ---- */
  const sidebar        = document.getElementById('sidebar');
  const sidebarOverlay = document.getElementById('sidebarOverlay');
  const sidebarToggle  = document.getElementById('sidebarToggle');
  const sidebarClose   = document.getElementById('sidebarClose');

  function openSidebar() {
    if (!sidebar) return;
    sidebar.classList.add('open');
    sidebarOverlay.classList.add('active');
    sidebarOverlay.removeAttribute('aria-hidden');
    sidebarToggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
    // Focus first focusable item inside sidebar
    const firstFocusable = sidebar.querySelector('a, button');
    if (firstFocusable) firstFocusable.focus();
  }

  function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove('open');
    sidebarOverlay.classList.remove('active');
    sidebarOverlay.setAttribute('aria-hidden', 'true');
    sidebarToggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
    sidebarToggle.focus();
  }

  if (sidebarToggle) sidebarToggle.addEventListener('click', openSidebar);
  if (sidebarClose)  sidebarClose.addEventListener('click', closeSidebar);
  if (sidebarOverlay) sidebarOverlay.addEventListener('click', closeSidebar);

  // Close sidebar on Escape key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      if (sidebar && sidebar.classList.contains('open')) closeSidebar();
      if (profileQuickView && profileQuickView.classList.contains('active')) closePQV();
    }
  });

  // Trap focus inside sidebar when open
  if (sidebar) {
    sidebar.addEventListener('keydown', (e) => {
      if (e.key !== 'Tab') return;
      const focusable = [...sidebar.querySelectorAll('a, button, [tabindex]:not([tabindex="-1"])')];
      const first = focusable[0];
      const last  = focusable[focusable.length - 1];
      if (e.shiftKey) {
        if (document.activeElement === first) { e.preventDefault(); last.focus(); }
      } else {
        if (document.activeElement === last)  { e.preventDefault(); first.focus(); }
      }
    });
  }


  /* ---- PROFILE QUICK VIEW ---- */
  const profileQuickViewBtn = document.getElementById('profileQuickViewBtn');
  const profileQuickView    = document.getElementById('profileQuickView');
  const pqvClose            = document.getElementById('pqvClose');
  const pqvBackdrop         = document.getElementById('pqvBackdrop');

  function openPQV() {
    if (!profileQuickView) return;
    profileQuickView.classList.add('active');
    profileQuickView.removeAttribute('aria-hidden');
    pqvBackdrop.classList.add('active');
    pqvBackdrop.removeAttribute('aria-hidden');
    profileQuickViewBtn.setAttribute('aria-expanded', 'true');
    // Focus close button
    if (pqvClose) pqvClose.focus();
  }

  function closePQV() {
    if (!profileQuickView) return;
    profileQuickView.classList.remove('active');
    profileQuickView.setAttribute('aria-hidden', 'true');
    pqvBackdrop.classList.remove('active');
    pqvBackdrop.setAttribute('aria-hidden', 'true');
    profileQuickViewBtn.setAttribute('aria-expanded', 'false');
    profileQuickViewBtn.focus();
  }

  if (profileQuickViewBtn) profileQuickViewBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    profileQuickView.classList.contains('active') ? closePQV() : openPQV();
  });
  if (pqvClose)    pqvClose.addEventListener('click', closePQV);
  if (pqvBackdrop) pqvBackdrop.addEventListener('click', closePQV);

  // Close PQV on outside click
  document.addEventListener('click', (e) => {
    if (
      profileQuickView &&
      profileQuickView.classList.contains('active') &&
      !profileQuickView.contains(e.target) &&
      e.target !== profileQuickViewBtn
    ) {
      closePQV();
    }
  });


  /* ---- STAT COUNTER ANIMATION ---- */
  function animateCounter(el) {
    const target = parseInt(el.getAttribute('data-target'), 10);
    if (isNaN(target)) return;
    const duration = 1200;
    const startTime = performance.now();

    function step(now) {
      const elapsed  = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      // Ease out cubic
      const eased = 1 - Math.pow(1 - progress, 3);
      el.textContent = Math.floor(eased * target);
      if (progress < 1) requestAnimationFrame(step);
      else el.textContent = target;
    }
    requestAnimationFrame(step);
  }

  // Use IntersectionObserver so counters animate when scrolled into view
  const statNumbers = document.querySelectorAll('.stat-number[data-target]');
  if ('IntersectionObserver' in window && statNumbers.length) {
    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    }, { threshold: 0.5 });
    statNumbers.forEach((el) => observer.observe(el));
  } else {
    // Fallback: just set the values
    statNumbers.forEach((el) => {
      el.textContent = el.getAttribute('data-target');
    });
  }


  /* ---- LIKE BUTTON TOGGLE ---- */
  document.addEventListener('click', (e) => {
    const likeBtn = e.target.closest('.pca-btn.like');
    if (!likeBtn) return;
    e.preventDefault();
    e.stopPropagation();
    const isLiked = likeBtn.classList.toggle('liked');
    likeBtn.setAttribute('aria-pressed', isLiked);
    // Animate the heart icon
    likeBtn.style.transform = 'scale(1.35)';
    setTimeout(() => { likeBtn.style.transform = ''; }, 200);
  });

  // Add CSS for liked state dynamically
  const likeStyle = document.createElement('style');
  likeStyle.textContent = `.pca-btn.like.liked { background: #e91e63 !important; color: white !important; }`;
  document.head.appendChild(likeStyle);


  /* ---- INTEREST BUTTON TOGGLE ---- */
  document.addEventListener('click', (e) => {
    const interestBtn = e.target.closest('.pca-btn.interest');
    if (!interestBtn) return;
    e.preventDefault();
    e.stopPropagation();
    const isSent = interestBtn.classList.toggle('sent');
    interestBtn.setAttribute('aria-pressed', isSent);
    interestBtn.style.transform = 'scale(1.35)';
    setTimeout(() => { interestBtn.style.transform = ''; }, 200);
  });

  const interestStyle = document.createElement('style');
  interestStyle.textContent = `.pca-btn.interest.sent { background: var(--color-primary) !important; color: white !important; }`;
  document.head.appendChild(interestStyle);


  /* ---- PROFILE CARD KEYBOARD NAVIGATION ---- */
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Enter' || e.key === ' ') {
      const card = e.target.closest('.profile-card');
      if (card) {
        e.preventDefault();
        // Simulate a click / navigate to profile
        card.dispatchEvent(new MouseEvent('click', { bubbles: true }));
      }
    }
  });


  /* ---- SCROLL TRACK — DRAG TO SCROLL (Desktop) ---- */
  document.querySelectorAll('.profile-scroll-track').forEach((track) => {
    let isDown   = false;
    let startX   = 0;
    let scrollLeft = 0;

    track.addEventListener('mousedown', (e) => {
      // Ignore clicks on buttons inside cards
      if (e.target.closest('button')) return;
      isDown = true;
      track.style.cursor = 'grabbing';
      startX = e.pageX - track.offsetLeft;
      scrollLeft = track.scrollLeft;
    });

    track.addEventListener('mouseleave', () => {
      isDown = false;
      track.style.cursor = '';
    });

    track.addEventListener('mouseup', () => {
      isDown = false;
      track.style.cursor = '';
    });

    track.addEventListener('mousemove', (e) => {
      if (!isDown) return;
      e.preventDefault();
      const x    = e.pageX - track.offsetLeft;
      const walk = (x - startX) * 1.5;
      track.scrollLeft = scrollLeft - walk;
    });
  });


  /* ---- SCROLL REVEAL ANIMATION ---- */
  const revealEls = document.querySelectorAll(
    '.stat-card, .profile-card, .pc-card, .upgrade-banner, .section-header'
  );

  if ('IntersectionObserver' in window && revealEls.length) {
    // Add initial hidden state via JS (not CSS) so it doesn't flash on slow loads
    revealEls.forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(16px)';
      el.style.transition = `opacity 420ms ease ${(i % 6) * 60}ms, transform 420ms cubic-bezier(0.16,1,0.3,1) ${(i % 6) * 60}ms`;
    });

    const revealObserver = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = '1';
          entry.target.style.transform = 'translateY(0)';
          revealObserver.unobserve(entry.target);
        }
      });
    }, { threshold: 0.1 });

    revealEls.forEach((el) => revealObserver.observe(el));
  }


  /* ---- LUCIDE ICONS INIT ---- */
  if (typeof lucide !== 'undefined') {
    lucide.createIcons();
  } else {
    // Retry after a short delay in case CDN is slow
    window.addEventListener('load', () => {
      if (typeof lucide !== 'undefined') lucide.createIcons();
    });
  }


  /* ---- LOGOUT BUTTON ---- */
  const logoutBtn = document.getElementById('logoutBtn');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', () => {
      if (confirm('Are you sure you want to logout?')) {
        // Redirect to login page
        window.location.href = 'login.html';
      }
    });
  }


  /* ---- REDUCED MOTION RESPECT ---- */
  if (matchMedia('(prefers-reduced-motion: reduce)').matches) {
    revealEls.forEach((el) => {
      el.style.opacity   = '1';
      el.style.transform = 'none';
      el.style.transition = 'none';
    });
  }

})();
