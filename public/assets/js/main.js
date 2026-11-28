document.addEventListener("DOMContentLoaded", () => {
  const revealItems = document.querySelectorAll("[data-reveal]");
  const observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        entry.target.classList.add("is-visible");
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.14 });

  revealItems.forEach((item) => observer.observe(item));

  document.querySelectorAll("[data-copy-target]").forEach((button) => {
    button.addEventListener("click", () => {
      const target = document.querySelector(button.dataset.copyTarget);
      if (!target) return;
      navigator.clipboard.writeText(target.textContent.trim()).then(() => {
        const old = button.textContent;
        button.textContent = "تم النسخ";
        setTimeout(() => { button.textContent = old; }, 1400);
      });
    });
  });

  const contactForm = document.getElementById("contactForm");
  if (contactForm) {
    contactForm.addEventListener("submit", (event) => {
      event.preventDefault();
      const data = new FormData(contactForm);
      const message = [
        contactForm.dataset.intro || "مرحبا، أريد الاستفسار عن خدماتكم",
        `الاسم: ${data.get("name") || ""}`,
        `الجوال: ${data.get("phone") || ""}`,
        `الخدمة: ${data.get("service") || ""}`,
        `نوع المساحة: ${data.get("space") || ""}`,
        `التفاصيل: ${data.get("message") || ""}`
      ].join("\n");

      // 1) حفظ الطلب في لوحة التحكم
      if (contactForm.action) {
        fetch(contactForm.action, {
          method: "POST",
          body: data,
          keepalive: true,
          headers: { Accept: "application/json", "X-Requested-With": "XMLHttpRequest" }
        }).catch(() => {});
      }

      // 2) فتح واتساب برسالة جاهزة
      const number = contactForm.dataset.whatsapp || "966535193153";
      window.open(`https://wa.me/${number}?text=${encodeURIComponent(message)}`, "_blank");

      const status = contactForm.querySelector(".contact-form-status");
      if (status && contactForm.dataset.success) {
        status.textContent = contactForm.dataset.success;
        status.style.display = "block";
      }
      contactForm.reset();
    });
  }

  if (window.jQuery && jQuery.fn.slick && jQuery(".finishing-track").length) {
    const $finishingTrack = jQuery(".finishing-track");

    if ($finishingTrack.hasClass("slick-initialized")) {
      $finishingTrack.slick("unslick");
    }

    $finishingTrack.slick({
      rtl: false,
      infinite: true,
      slidesToShow: 5,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 0,
      speed: 5200,
      cssEase: "linear",
      arrows: false,
      dots: false,
      pauseOnHover: false,
      pauseOnFocus: false,
      swipe: true,
      draggable: true,
      variableWidth: false,
      responsive: [
        { breakpoint: 1024, settings: { slidesToShow: 4 } },
        { breakpoint: 768, settings: { slidesToShow: 3 } },
        { breakpoint: 520, settings: { slidesToShow: 2 } }
      ]
    });

    // الشرائح المخفية عن قارئات الشاشة يجب ألا تكون قابلة للتركيز بلوحة المفاتيح
    const syncSlideFocus = () => {
      $finishingTrack.find(".slick-slide").each(function () {
        this.setAttribute("tabindex", this.getAttribute("aria-hidden") === "true" ? "-1" : "0");
      });
    };
    syncSlideFocus();
    $finishingTrack.on("afterChange", syncSlideFocus);
  }

});
