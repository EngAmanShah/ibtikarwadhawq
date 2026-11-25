/* سلوك القائمة العلوية وقائمة الجوال وزر الواتساب العائم (المحتوى نفسه يُبنى من لوحة التحكم) */
(function () {
  const $ = (id) => document.getElementById(id);
  const header = $("siteHeader");
  const mobileMenu = $("mobileMenu");
  const mobileLayer = $("mobileLayer");
  const mobileServices = $("mobileServices");
  const mobileServicesToggle = $("mobileServicesToggle");
  const floatingToggle = $("floatingContactToggle");
  const floatingPanel = $("floatingWhatsappPanel");

  floatingToggle && floatingToggle.addEventListener("click", () => {
    const isOpen = floatingPanel && floatingPanel.classList.toggle("open");
    floatingToggle.setAttribute("aria-expanded", isOpen ? "true" : "false");
  });

  document.addEventListener("click", (event) => {
    if (!floatingPanel || !floatingToggle) return;
    const wrapper = document.querySelector(".floating-contact-menu");
    if (wrapper && !wrapper.contains(event.target)) {
      floatingPanel.classList.remove("open");
      floatingToggle.setAttribute("aria-expanded", "false");
    }
  });

  function setHeader() {
    header && header.classList.toggle("is-scrolled", window.scrollY > 24);
  }
  function openMenu() {
    mobileMenu.classList.add("open");
    mobileLayer.classList.add("open");
    document.body.classList.add("menu-open");
  }
  function closeMenu() {
    mobileMenu.classList.remove("open");
    mobileLayer.classList.remove("open");
    document.body.classList.remove("menu-open");
  }

  window.addEventListener("scroll", setHeader, { passive: true });
  setHeader();
  $("menuToggle") && $("menuToggle").addEventListener("click", openMenu);
  $("menuClose") && $("menuClose").addEventListener("click", closeMenu);
  mobileLayer && mobileLayer.addEventListener("click", closeMenu);
  mobileServicesToggle && mobileServicesToggle.addEventListener("click", () => {
    mobileServices.classList.toggle("open");
    mobileServicesToggle.classList.toggle("open");
  });
})();
