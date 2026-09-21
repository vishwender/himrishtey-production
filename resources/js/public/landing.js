document.addEventListener("DOMContentLoaded", () => {
  if (window.lucide) {
    window.lucide.createIcons();
  }
  const header = document.getElementById("site-header");
  const onScrollHeader = () => {
    if (!header) return;
    if (window.scrollY > 10) {
      header.classList.add("scrolled");
    } else {
      header.classList.remove("scrolled");
    }
  };
  onScrollHeader();
  window.addEventListener("scroll", onScrollHeader);

  const drawer = document.getElementById("mobileDrawer");
  const drawerOverlay = document.getElementById("drawerOverlay");
  const hamburgerBtn = document.getElementById("hamburgerBtn");
  const drawerClose = document.getElementById("drawerClose");
  const drawerLinks = document.querySelectorAll(".drawer-links a");

  const openDrawer = () => {
    if (!drawer || !drawerOverlay || !hamburgerBtn) return;
    drawer.classList.add("open");
    drawerOverlay.classList.add("show");
    drawer.setAttribute("aria-hidden", "false");
    hamburgerBtn.setAttribute("aria-expanded", "true");
    document.body.style.overflow = "hidden";
  };

  const closeDrawer = () => {
    if (!drawer || !drawerOverlay || !hamburgerBtn) return;
    drawer.classList.remove("open");
    drawerOverlay.classList.remove("show");
    drawer.setAttribute("aria-hidden", "true");
    hamburgerBtn.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
  };

  if (hamburgerBtn) hamburgerBtn.addEventListener("click", openDrawer);
  if (drawerClose) drawerClose.addEventListener("click", closeDrawer);
  if (drawerOverlay) drawerOverlay.addEventListener("click", closeDrawer);

  drawerLinks.forEach(link => {
    link.addEventListener("click", closeDrawer);
  });

  document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeDrawer();
  });

  const revealElements = document.querySelectorAll(".reveal");
  const revealObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.classList.add("in-view");
          revealObserver.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.14 }
  );

  revealElements.forEach((el) => revealObserver.observe(el));

  const statNumbers = document.querySelectorAll(".stat-number");
  const statsObserver = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (!entry.isIntersecting) return;

        const el = entry.target;
        const target = parseInt(el.getAttribute("data-target"), 10) || 0;
        let start = 0;
        const duration = 1500;
        const startTime = performance.now();

        const updateCount = (now) => {
          const progress = Math.min((now - startTime) / duration, 1);
          const value = Math.floor(progress * target);
          el.textContent = value.toLocaleString("en-IN");

          if (progress < 1) {
            requestAnimationFrame(updateCount);
          } else {
            el.textContent = target.toLocaleString("en-IN");
          }
        };

        requestAnimationFrame(updateCount);
        statsObserver.unobserve(el);
      });
    },
    { threshold: 0.5 }
  );

  statNumbers.forEach((stat) => statsObserver.observe(stat));

  const contactForm = document.getElementById("contactForm");
  const formSuccess = document.getElementById("formSuccess");

  if (contactForm) {
    contactForm.addEventListener("submit", (e) => {
      e.preventDefault();

      const inputs = contactForm.querySelectorAll("input[required], textarea[required]");
      let valid = true;

      inputs.forEach((input) => {
        if (!input.value.trim()) {
          input.style.borderColor = "#d92768";
          valid = false;
        } else {
          input.style.borderColor = "";
        }
      });

      if (!valid) return;

      if (formSuccess) {
        formSuccess.hidden = false;
        if (window.lucide) {
          window.lucide.createIcons();
        }
      }

      contactForm.reset();
    });
  }

  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      const targetId = this.getAttribute("href");
      if (!targetId || targetId === "#") return;

      const target = document.querySelector(targetId);
      if (!target) return;

      e.preventDefault();
      target.scrollIntoView({ behavior: "smooth", block: "start" });
    });
  });
});