/* profile-detail.js — Profile Detail page logic
   Dark/light mode is handled by script.js (already loaded globally) */

document.addEventListener("DOMContentLoaded", () => {
  if (window.lucide) window.lucide.createIcons();

  initCarousel();
  initGallery();
  initLikeShortlist();
  initBottomBar();
});

/* ── CAROUSEL ── */
function initCarousel() {
  const slides = document.querySelectorAll(".pd-slide");
  const dotsContainer = document.getElementById("slideDots");
  const prevBtn = document.getElementById("slidePrev");
  const nextBtn = document.getElementById("slideNext");
  let current = 0;
  let autoTimer;

  if (!slides.length) return;

  // Build dots
  slides.forEach((_, i) => {
    const dot = document.createElement("button");
    dot.className = "pd-dot" + (i === 0 ? " active" : "");
    dot.setAttribute("aria-label", `Photo ${i + 1}`);
    dot.addEventListener("click", () => goTo(i));
    dotsContainer.appendChild(dot);
  });

  function goTo(index) {
    slides[current].classList.remove("active");
    dotsContainer.children[current].classList.remove("active");
    current = (index + slides.length) % slides.length;
    slides[current].classList.add("active");
    dotsContainer.children[current].classList.add("active");
  }

  function startAuto() {
    autoTimer = setInterval(() => goTo(current + 1), 5000);
  }

  function stopAuto() {
    clearInterval(autoTimer);
  }

  prevBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    stopAuto();
    goTo(current - 1);
    startAuto();
  });

  nextBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    stopAuto();
    goTo(current + 1);
    startAuto();
  });

  // Swipe support
  let touchStartX = 0;
  const carousel = document.getElementById("heroCarousel");
  carousel?.addEventListener("touchstart", (e) => {
    touchStartX = e.changedTouches[0].screenX;
  }, { passive: true });
  carousel?.addEventListener("touchend", (e) => {
    const diff = touchStartX - e.changedTouches[0].screenX;
    if (Math.abs(diff) > 40) {
      stopAuto();
      diff > 0 ? goTo(current + 1) : goTo(current - 1);
      startAuto();
    }
  });

  startAuto();
}

/* ── GALLERY LIGHTBOX ── */
function initGallery() {
  const overlay = document.getElementById("galleryOverlay");
  const closeBtn = document.getElementById("galleryClose");
  const galleryBtn = document.getElementById("galleryBtn");
  const galleryActionBtn = document.getElementById("galleryActionBtn");
  const uploadInput = document.getElementById("galleryPhotoInput");

  galleryBtn?.addEventListener("click", openGallery);
  galleryActionBtn?.addEventListener("click", openGallery);
  closeBtn?.addEventListener("click", closeGallery);
  overlay?.addEventListener("click", (e) => {
    if (e.target === overlay) closeGallery();
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
      closeGallery();
      closeUnlockModal();
      closeReportSheet();
    }
  });

  uploadInput?.addEventListener("change", async () => {
    const files = [...uploadInput.files];
    const uploadUrl = overlay?.dataset.uploadUrl;

    if (!files.length || !uploadUrl) return;

    if (files.length > 5) {
      showToast("Please select no more than five photos at once.");
      uploadInput.value = "";
      return;
    }

    const formData = new FormData();
    files.forEach(file => formData.append("photos[]", file));

    try {
      const response = await fetch(uploadUrl, {
        method: "POST",
        headers: {
          "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
          "Accept": "application/json"
        },
        body: formData
      });
      const result = await response.json();

      if (!response.ok) throw new Error(result.message || "Unable to upload photos.");

      window.location.reload();
    } catch (error) {
      showToast(error.message || "Unable to upload photos.");
      uploadInput.value = "";
    }
  });

  document.querySelectorAll(".pd-gallery-remove").forEach(button => {
    button.addEventListener("click", async () => {
      if (!window.confirm("Remove this photo?")) return;

      const deleteUrl = overlay?.dataset.deleteUrl?.replace("__PHOTO__", button.dataset.photoId);
      if (!deleteUrl) return;

      button.disabled = true;

      try {
        const response = await fetch(deleteUrl, {
          method: "DELETE",
          headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.content || "",
            "Accept": "application/json"
          }
        });
        const result = await response.json();

        if (!response.ok) throw new Error(result.message || "Unable to remove photo.");

        window.location.reload();
      } catch (error) {
        button.disabled = false;
        showToast(error.message || "Unable to remove photo.");
      }
    });
  });
}

function openGallery() {
  const overlay = document.getElementById("galleryOverlay");
  overlay?.classList.add("open");
  overlay?.setAttribute("aria-hidden", "false");
  if (window.lucide) window.lucide.createIcons();
}

function closeGallery() {
  const overlay = document.getElementById("galleryOverlay");
  overlay?.classList.remove("open");
  overlay?.setAttribute("aria-hidden", "true");
}

/* ── LIKE & SHORTLIST (hero fabs) ── */
function initLikeShortlist() {
  initProfileLike();
  const shortlistBtn = document.getElementById("shortlistBtn");

  shortlistBtn?.addEventListener("click", (e) => {
    e.stopPropagation();
    shortlistBtn.classList.toggle("active");
    showToast(shortlistBtn.classList.contains("active") ? "🔖 Shortlisted!" : "Removed from shortlist");
  });
}

/* ── SHORTLIST (aside card) ── */
function toggleShortlist() {

    const btn = document.getElementById("asideShortlistBtn");

    if (!btn) return;

    const profileId = btn.dataset.profileId;

    btn.disabled = true;

    fetch("/short-profile", {
        method: "POST",
        headers: {
            "Accept": "application/json",
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
        },
        body: JSON.stringify({
            id: profileId
        })
    })
    .then(async response => {

        const data = await response.json();

        btn.disabled = false;

        if (!response.ok) {
            showToast(data.message || "Something went wrong.");
            return;
        }

        showToast(data.message);

        btn.classList.add("active");
        btn.innerHTML = `
            <i data-lucide="bookmark-check" width="17" height="17"></i>
            Shortlisted
        `;

        lucide.createIcons();

    })
    .catch(err => {

        btn.disabled = false;
        console.error(err);
        showToast("Something went wrong.");

    });

}

function checkShortlistStatus() {
    const button = document.getElementById('asideShortlistBtn');

    if (!button) return;

    const profileId = button.dataset.profileId;

    fetch(`/check-shortlist?id=${profileId}`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.shortlisted) {
            button.classList.add('active');
            button.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg"
                    width="17"
                    height="17"
                    viewBox="0 0 24 24"
                    fill="currentColor"
                    stroke="currentColor"
                    stroke-width="2">
                    <path d="M17 3a2 2 0 0 1 2 2v15a1 1 0 0 1-1.496.868l-4.512-2.578a2 2 0 0 0-1.984 0l-4.512 2.578A1 1 0 0 1 5 20V5a2 2 0 0 1 2-2z"></path>
                </svg>
                Shortlisted
            `;
        }
    })
    .catch(error => {
        console.error('Shortlist check failed:', error);
    });
}

document.addEventListener('DOMContentLoaded', function () {
    checkShortlistStatus();
});

// Keep the heart state synchronized with the saved like state.
function initProfileLike() {
  const button = document.getElementById('likeBtn');
  if (!button) return;

  const setLiked = (liked) => {
    button.classList.remove('active');
    button.classList.toggle('liked', liked);
    button.setAttribute('aria-pressed', String(liked));
    button.setAttribute('aria-label', liked ? 'Unlike profile' : 'Like profile');
    button.setAttribute('title', liked ? 'Unlike profile' : 'Like profile');
  };

  const readResponse = async (response) => {
    if (!response.ok) throw new Error('Unable to update like status.');
    return response.json();
  };

  button.disabled = true;
  fetch(`/check-profile-like/${button.dataset.profileId}`, {
    headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
  })
    .then(readResponse)
    .then(data => setLiked(data.liked === true))
    .catch(error => console.error('Unable to check like status:', error))
    .finally(() => { button.disabled = false; });

  button.addEventListener('click', async (event) => {
    event.stopPropagation();
    if (button.disabled) return;
    button.disabled = true;
    try {
      const response = await fetch('/like-profile', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          Accept: 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ id: button.dataset.profileId })
      });
      const data = await readResponse(response);
      if (!['liked', 'unliked'].includes(data.status)) throw new Error('Unexpected like status.');
      const liked = data.status === 'liked';
      setLiked(liked);
      showToast(liked ? '❤️ Liked!' : 'Like removed');
    } catch (error) {
      console.error('Like error:', error);
      showToast('Unable to update like. Please try again.');
    } finally {
      button.disabled = false;
    }
  });
}

/* ── INTEREST ACTIONS ── */
let interestState = "none"; // none | sent | received | matched | rejected

function handleInterestAction() {

    if (interestState === "none") {

        const profileId = document.getElementById("sendInterestBtn").dataset.profileId;
        console.log(profileId);

        fetch(`/send-interest/${profileId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({
                status: 0
            })
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {

            if (res.status === 200) {

                interestState = "sent";
                updateInterestUI();
                showToast(res.body.message);

            } else {

                showToast(res.body.message);

            }

        })
        .catch(error => {
            console.error(error);
            showToast("Something went wrong. Please try again.");
        });

    } else if (interestState === "sent") {

        if (confirm("Delete the interest you sent?")) {

            // Call delete interest API here if you have one
            interestState = "none";
            updateInterestUI();
            showToast("Interest deleted");

        }

    }
}

function handleReject() {
  interestState = "rejected";
  updateInterestUI();
  showToast("Interest rejected");
}

function updateInterestUI() {
  const sendBtn = document.getElementById("sendInterestBtn");
  const bottomBtn = document.getElementById("bottomInterestBtn");
  const bottomLabel = document.getElementById("bottomInterestLabel");
  const rejectBtn = document.getElementById("rejectBtn");

  const states = {
    none: {
      label: "Send Interest",
      icon: "send",
      color: "",
      showReject: false,
    },
    sent: {
      label: "Interest Sent — Delete?",
      icon: "clock",
      color: "#ea580c",
      showReject: false,
    },
    received: {
      label: "Accept Interest",
      icon: "check",
      color: "#16a34a",
      showReject: true,
    },
    matched: {
      label: "Matched ❤️",
      icon: "heart",
      color: "#D92768",
      showReject: false,
    },
    rejected: {
      label: "Rejected",
      icon: "x-circle",
      color: "#dc2626",
      showReject: false,
    },
  };

  const s = states[interestState] || states.none;

  if (sendBtn) {
    sendBtn.style.background = s.color || "var(--color-primary, #D92768)";
    sendBtn.innerHTML = `<i data-lucide="${s.icon}" width="17" height="17"></i> ${s.label}`;
  }
  if (bottomBtn) {
    bottomBtn.style.background = s.color || "var(--color-primary, #D92768)";
  }
  if (bottomLabel) bottomLabel.textContent = s.label;
  if (rejectBtn) rejectBtn.style.display = s.showReject ? "flex" : "none";

  if (window.lucide) window.lucide.createIcons();
}

/* ── BOTTOM BAR (scroll-show logic) ── */
function initBottomBar() {
  // Already always visible on mobile via CSS — no JS needed for basic show
}

/* ── UNLOCK MODAL ── */
/* ── UNLOCK MODAL ── */

let currentUnlockType = null;
let unlockModalTrigger = null;


/**
 * Open unlock modal
 */
function openUnlockModal(type) {

    currentUnlockType = type;

    const overlay =
        document.getElementById("unlockModalOverlay");

    const title =
        document.getElementById("unlockModalTitle");

    const confirmButton =
        document.getElementById("unlockConfirmBtn");

    if (!overlay) {
        console.error("unlockModalOverlay not found");
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | Remember which button opened the modal
    |--------------------------------------------------------------------------
    */

    unlockModalTrigger = document.activeElement;


    /*
    |--------------------------------------------------------------------------
    | Set modal title
    |--------------------------------------------------------------------------
    */

    if (title) {

        title.textContent = "Unlock Contact Details";
    }


    /*
    |--------------------------------------------------------------------------
    | Show modal
    |--------------------------------------------------------------------------
    */

    overlay.classList.add("open");

    overlay.setAttribute(
        "aria-hidden",
        "false"
    );

    document.body.style.overflow = "hidden";


    /*
    |--------------------------------------------------------------------------
    | Focus Unlock button
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        if (confirmButton) {
            confirmButton.focus();
        }

    }, 50);
}


/**
 * Close unlock modal
 */
function closeUnlockModal() {

    const overlay =
        document.getElementById("unlockModalOverlay");

    if (!overlay) return;


    /*
    |--------------------------------------------------------------------------
    | IMPORTANT
    | Remove focus from anything inside modal BEFORE aria-hidden=true
    |--------------------------------------------------------------------------
    */

    const activeElement =
        document.activeElement;

    if (
        activeElement &&
        overlay.contains(activeElement)
    ) {

        activeElement.blur();
    }


    /*
    |--------------------------------------------------------------------------
    | Hide modal
    |--------------------------------------------------------------------------
    */

    overlay.classList.remove("open");

    overlay.setAttribute(
        "aria-hidden",
        "true"
    );

    document.body.style.overflow = "";


    /*
    |--------------------------------------------------------------------------
    | Restore focus to original button
    |--------------------------------------------------------------------------
    */

    const trigger =
        unlockModalTrigger;

    unlockModalTrigger = null;

    if (
        trigger &&
        trigger !== document.body &&
        document.contains(trigger)
    ) {

        setTimeout(() => {

            trigger.focus();

        }, 0);
    }
}


/**
 * Confirm unlock
 */
async function confirmUnlock() {

    const button =
        document.getElementById("unlockConfirmBtn");

    if (!button) {

        console.error(
            "unlockConfirmBtn not found"
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Get profile ID and price
    |--------------------------------------------------------------------------
    */

    const profileId = button.dataset.profileId;
    const unlockUrl = button.dataset.unlockUrl;

    const unlockPrice = button.dataset.unlockPrice;


    console.log(
        "Unlock profile:",
        profileId
    );

    console.log(
        "Unlock price:",
        unlockPrice
    );


    if (!profileId || !unlockUrl) {

        showToast(
            "Contact unlock configuration is missing."
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Save unlock type BEFORE closing modal
    |--------------------------------------------------------------------------
    */

    const unlockType =
        currentUnlockType;


    /*
    |--------------------------------------------------------------------------
    | Disable button
    |--------------------------------------------------------------------------
    */

    button.disabled = true;


    /*
    |--------------------------------------------------------------------------
    | Close modal
    |--------------------------------------------------------------------------
    */

    closeUnlockModal();


    /*
    |--------------------------------------------------------------------------
    | CONTACT UNLOCK
    |--------------------------------------------------------------------------
    */

    if (unlockType === "contact") {

        try {

            showToast(
                "Unlocking contact details..."
            );


            const csrfToken =
                document
                    .querySelector(
                        'meta[name="csrf-token"]'
                    )
                    ?.getAttribute("content");


            if (!csrfToken) {

                throw new Error(
                    "CSRF token not found."
                );
            }


            const response =
                await fetch(
                    unlockUrl,
                    {
                        method: "POST",

                        headers: {

                            "Content-Type":
                                "application/json",

                            "Accept":
                                "application/json",

                            "X-CSRF-TOKEN":
                                csrfToken
                        },

                        body: JSON.stringify({})
                    }
                );


            const data = await response.json();

            console.log(
                "Unlock response:",
                data
            );


            /*
            |--------------------------------------------------------------------------
            | API ERROR
            |--------------------------------------------------------------------------
            */

            if (
                !response.ok ||
                data.status !== "success"
            ) {

                throw new Error(
                    data.message ||
                    "Unable to unlock contact details."
                );
            }


            /*
            |--------------------------------------------------------------------------
            | MOBILE
            |--------------------------------------------------------------------------
            */

            const mobile =
                document.getElementById(
                    "mobileValue"
                );

            if (mobile) {

                mobile.classList.remove(
                    "pd-locked"
                );

                mobile.textContent =
                    data.mobile_number || "-";
            }


            /*
            |--------------------------------------------------------------------------
            | WHATSAPP
            |--------------------------------------------------------------------------
            */

            const whatsapp =
                document.getElementById(
                    "waValue"
                );

            if (whatsapp) {

                whatsapp.classList.remove(
                    "pd-locked"
                );

                whatsapp.textContent =
                    data.whatsapp_number || "-";
            }


            /*
            |--------------------------------------------------------------------------
            | EMAIL
            |--------------------------------------------------------------------------
            */

            const email =
                document.getElementById(
                    "emailValue"
                );

            if (email) {

                email.classList.remove(
                    "pd-locked"
                );

                email.textContent =
                    data.email || "-";
            }


            /*
            |--------------------------------------------------------------------------
            | Remove unlock UI
            |--------------------------------------------------------------------------
            */

            document
                .getElementById(
                    "contactUnlock"
                )
                ?.remove();


            document
                .querySelectorAll(
                    ".pd-row-lock"
                )
                .forEach(
                    el => el.remove()
                );


            /*
            |--------------------------------------------------------------------------
            | Update wallet balance
            |--------------------------------------------------------------------------
            */

            const walletBalance =
                document.getElementById(
                    "walletBalance"
                );

            if (
                walletBalance &&
                data.wallet_balance !== undefined
            ) {

                walletBalance.textContent =
                    "₹ " +
                    data.wallet_balance;
            }


            /*
            |--------------------------------------------------------------------------
            | Success
            |--------------------------------------------------------------------------
            */

            showToast(
                "🔓 Contact details unlocked!"
            );


        } catch (error) {

            console.error(
                "Contact unlock error:",
                error
            );

            showToast(
                error.message ||
                "Unable to unlock contact details."
            );


        } finally {

            button.disabled = false;
        }

        return;
    }

}

/* ── REPORT SHEET ── */
document.getElementById("reportBtn")?.addEventListener("click", openReportSheet);

function openReportSheet() {
  const overlay = document.getElementById("reportSheetOverlay");
  overlay?.classList.add("open");
  overlay?.setAttribute("aria-hidden", "false");
  document.body.style.overflow = "hidden";
}

function closeReportSheet() {
  const overlay = document.getElementById("reportSheetOverlay");
  overlay?.classList.remove("open");
  overlay?.setAttribute("aria-hidden", "true");
  document.body.style.overflow = "";
}

document.getElementById("reportSheetOverlay")?.addEventListener("click", (e) => {
  if (e.target === document.getElementById("reportSheetOverlay")) closeReportSheet();
});

function submitReport(btn) {
  closeReportSheet();
  showToast("✅ Report submitted. Thank you!");
}

/* ── SHARE ── */
document.getElementById("shareBtn")?.addEventListener("click", shareProfile);
const profileShareDialog = document.getElementById("profileShareDialog");
profileShareDialog?.addEventListener("close", () => document.body.classList.remove("pd-sharing"));
profileShareDialog?.addEventListener("click", (event) => {
    const bounds = profileShareDialog.getBoundingClientRect();
    if (event.target === profileShareDialog && (event.clientX < bounds.left || event.clientX > bounds.right || event.clientY < bounds.top || event.clientY > bounds.bottom)) {
        profileShareDialog.close();
    }
});

function shareProfile() {
    const btn = document.getElementById("shareBtn");

    if (!btn) return;

    const url = btn.dataset.url;
    if (!url) return;

    const dialog = document.getElementById("profileShareDialog");
    document.getElementById("profileShareUrl").value = url;
    document.getElementById("profileSharePreview").href = url;
    document.getElementById("profileShareWhatsApp").href =
        `https://wa.me/?text=${encodeURIComponent(whatsAppProfileText(btn))}`;
    document.getElementById("profileShareStatus").textContent = "";
    dialog.showModal();
    document.body.classList.add("pd-sharing");
}

function copyProfileLink() {
    const url = document.getElementById("shareBtn")?.dataset.url;
    if (!url) return;

    if (navigator.clipboard) {
        navigator.clipboard.writeText(url)
            .then(() => {
                showToast("🔗 Profile link copied!");
            })
            .catch(() => {
                fallbackCopy(url);
            });
    } else {
        fallbackCopy(url);
    }
}

function fallbackCopy(text) {
    const textarea = document.createElement("textarea");

    textarea.value = text;
    textarea.style.position = "fixed";
    textarea.style.opacity = "0";

    (document.getElementById("profileShareDialog") || document.body).appendChild(textarea);
    textarea.select();

    try {
        if (!document.execCommand("copy")) throw new Error("Copy unavailable");
        showToast("🔗 Profile link copied!");
    } catch (error) {
        document.getElementById("profileShareUrl")?.select();
        showToast("Select and copy the shareable link above.");
    }

    textarea.remove();
}

function whatsAppProfileText(btn) {
    return btn.dataset.shareText || "";
}

function shareToWhatsApp(btn) {
    const text = whatsAppProfileText(btn);
    window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, "_blank", "noopener,noreferrer");
}

/* ── TOAST ── */
let toastTimer;
function showToast(message) {
  if (document.getElementById("profileShareDialog")?.open) {
    document.getElementById("profileShareStatus").textContent = message;
  }
  const toast = document.getElementById("pdToast");
  if (!toast) return;
  clearTimeout(toastTimer);
  toast.textContent = message;
  toast.classList.add("show");
  toastTimer = setTimeout(() => toast.classList.remove("show"), 2800);
}
