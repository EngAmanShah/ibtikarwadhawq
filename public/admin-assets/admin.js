/* =====================================================================
   لوحة تحكم ابتكار وذوق — التفاعلات
   ===================================================================== */
(function () {
  "use strict";
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => Array.from(r.querySelectorAll(s));
  const csrf = () => ($('meta[name="csrf-token"]') || {}).content;

  /* ---------------- التنبيهات ---------------- */
  window.toast = function (msg, type = "ok") {
    let box = $(".toasts");
    if (!box) { box = document.createElement("div"); box.className = "toasts"; document.body.appendChild(box); }
    const t = document.createElement("div");
    t.className = "toast " + (type === "error" ? "error" : "");
    t.innerHTML = `<i class="t-ico fa-solid ${type === "error" ? "fa-triangle-exclamation" : "fa-check"}"></i><span></span><button type="button" aria-label="إغلاق"><i class="fa-solid fa-xmark"></i></button>`;
    t.querySelector("span").textContent = msg;
    box.appendChild(t);
    const close = () => { t.classList.add("hide"); setTimeout(() => t.remove(), 400); };
    t.querySelector("button").onclick = close;
    setTimeout(close, 4200);
  };
  $$(".toast").forEach((t) => {
    const close = () => { t.classList.add("hide"); setTimeout(() => t.remove(), 400); };
    const b = t.querySelector("button"); b && (b.onclick = close);
    setTimeout(close, 4500);
  });

  /* ---------------- القائمة الجانبية للجوال ---------------- */
  $$("[data-rail-toggle]").forEach((b) => b.addEventListener("click", () => document.body.classList.toggle("rail-open")));

  /* ---------------- نافذة التأكيد ---------------- */
  const modal = $("#confirmModal");
  let pendingForm = null;
  document.addEventListener("submit", (e) => {
    const f = e.target;
    if (f.matches("[data-confirm]") && !f.dataset.confirmed && modal) {
      e.preventDefault();
      pendingForm = f;
      $("#confirmTitle").textContent = f.dataset.confirm || "هل أنت متأكد؟";
      $("#confirmText").textContent = f.dataset.confirmText || "لا يمكن التراجع عن هذا الإجراء.";
      modal.classList.add("open");
      setTimeout(() => $("#confirmYes").focus(), 50);
    }
  }, true);
  if (modal) {
    $("#confirmYes").onclick = () => { if (pendingForm) { pendingForm.dataset.confirmed = "1"; pendingForm.requestSubmit ? pendingForm.requestSubmit() : pendingForm.submit(); } modal.classList.remove("open"); };
    $$("[data-modal-close]", modal).forEach((b) => (b.onclick = () => modal.classList.remove("open")));
    modal.addEventListener("click", (e) => { if (e.target === modal) modal.classList.remove("open"); });
  }

  /* ---------------- حالة التحميل عند الإرسال ---------------- */
  document.addEventListener("submit", (e) => {
    if (e.defaultPrevented) return;
    const btn = e.target.querySelector("[data-loading]") || (e.submitter && e.submitter.matches("[data-loading]") ? e.submitter : null);
    if (btn) { btn.classList.add("loading"); setTimeout(() => (btn.disabled = true), 10); }
  });

  /* ---------------- تتبع التغييرات + شريط الحفظ ---------------- */
  $$("form[data-track]").forEach((form) => {
    const dock = $("#saveDock");
    let dirty = false;
    const mark = () => { if (!dirty) { dirty = true; dock && dock.classList.add("show"); } };
    form.addEventListener("input", mark);
    form.addEventListener("change", mark);
    form.addEventListener("cms:dirty", mark);
    form.addEventListener("submit", () => { dirty = false; });
    window.addEventListener("beforeunload", (e) => { if (dirty) { e.preventDefault(); e.returnValue = ""; } });
    if (dock) {
      const save = $("[data-dock-save]", dock);
      const undo = $("[data-dock-undo]", dock);
      save && save.addEventListener("click", () => { dirty = false; save.classList.add("loading"); form.requestSubmit ? form.requestSubmit() : form.submit(); });
      undo && undo.addEventListener("click", () => { dirty = false; location.reload(); });
    }
    document.addEventListener("keydown", (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "s") { e.preventDefault(); dirty = false; form.requestSubmit ? form.requestSubmit() : form.submit(); }
    });
  });

  /* ---------------- حقل الصور والملفات ---------------- */
  $$(".dropzone").forEach((dz) => {
    const input = $("input[type=file]", dz);
    if (!input) return;
    ["dragenter", "dragover"].forEach((ev) => dz.addEventListener(ev, (e) => { e.preventDefault(); dz.classList.add("drag"); }));
    ["dragleave", "drop"].forEach((ev) => dz.addEventListener(ev, () => dz.classList.remove("drag")));
    dz.addEventListener("drop", (e) => {
      e.preventDefault();
      if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; input.dispatchEvent(new Event("change", { bubbles: true })); }
    });
    input.addEventListener("change", () => {
      const file = input.files && input.files[0];
      if (!file) return;
      const maxMb = parseFloat(input.dataset.max || "10");
      if (file.size > maxMb * 1024 * 1024) {
        toast(`حجم الملف كبير (${(file.size / 1048576).toFixed(1)} ميجا). الحد الأقصى ${maxMb} ميجا.`, "error");
        input.value = ""; return;
      }
      dz.classList.add("changed");
      if (file.type.startsWith("image/")) {
        const url = URL.createObjectURL(file);
        let img = $("img", dz);
        const empty = $(".dz-empty", dz); empty && empty.remove();
        if (!img) { img = document.createElement("img"); dz.prepend(img); }
        img.src = url;
      } else {
        const chip = $(".doc-chip", dz) || $(".dz-empty", dz);
        if (chip) chip.outerHTML = `<div class="doc-chip"><i class="fa-solid fa-file-pdf"></i><div><strong></strong><small>${(file.size / 1048576).toFixed(2)} ميجا — سيتم رفعه عند الحفظ</small></div></div>`;
        const s = $(".doc-chip strong", dz); s && (s.textContent = file.name);
      }
    });
  });

  /* ---------------- حقل القائمة ---------------- */
  $$(".list-field").forEach((lf) => {
    const name = lf.dataset.name;
    const rows = $(".list-rows", lf);
    const addRow = (val = "") => {
      const r = document.createElement("div");
      r.className = "list-row";
      r.innerHTML = `<span class="handle"><i class="fa-solid fa-grip-vertical"></i></span><input class="input" name="${name}[]" /><button type="button" class="btn btn-danger btn-icon" aria-label="حذف"><i class="fa-solid fa-trash-can"></i></button>`;
      r.querySelector("input").value = val;
      rows.appendChild(r);
      return r;
    };
    lf.addEventListener("click", (e) => {
      const del = e.target.closest(".list-row .btn-danger");
      if (del) { del.closest(".list-row").remove(); lf.closest("form") && lf.closest("form").dispatchEvent(new Event("cms:dirty")); }
      if (e.target.closest(".list-add")) { const r = addRow(); r.querySelector("input").focus(); lf.closest("form") && lf.closest("form").dispatchEvent(new Event("cms:dirty")); }
    });
    lf.addEventListener("keydown", (e) => {
      if (e.key === "Enter" && e.target.matches(".list-row input")) {
        e.preventDefault();
        const r = addRow(); e.target.closest(".list-row").after(r); r.querySelector("input").focus();
      }
    });
    if (window.Sortable) Sortable.create(rows, { handle: ".handle", animation: 180, onEnd: () => lf.closest("form") && lf.closest("form").dispatchEvent(new Event("cms:dirty")) });
  });

  /* ---------------- عداد الأحرف ---------------- */
  $$("[data-counter]").forEach((el) => {
    const max = parseInt(el.dataset.counter, 10);
    const box = el.parentElement.querySelector(".counter");
    if (!box) return;
    const bar = $(".counter-bar span", box), txt = $(".counter-text", box);
    const upd = () => {
      const n = el.value.length;
      bar.style.width = Math.min(100, (n / max) * 100) + "%";
      box.classList.toggle("warn", n > max * 0.9 && n <= max);
      box.classList.toggle("over", n > max);
      txt.textContent = `${n} / ${max}`;
    };
    el.addEventListener("input", upd); upd();
  });

  /* ---------------- معاينة جوجل ---------------- */
  $$("[data-serp]").forEach((serp) => {
    const t = $(serp.dataset.titleFrom), d = $(serp.dataset.descFrom);
    const upd = () => {
      if (t) $(".serp-title", serp).textContent = t.value || "عنوان الصفحة";
      if (d) $(".serp-desc", serp).textContent = d.value || "وصف الصفحة يظهر هنا...";
    };
    t && t.addEventListener("input", upd); d && d.addEventListener("input", upd); upd();
  });

  /* ---------------- حقل اللون ---------------- */
  $$(".color-field").forEach((cf) => {
    const c = $("input[type=color]", cf), t = $("input[type=text]", cf);
    c.addEventListener("input", () => (t.value = c.value));
    t.addEventListener("input", () => { if (/^#[0-9a-f]{6}$/i.test(t.value)) c.value = t.value; });
    $$(".color-presets button", cf.parentElement).forEach((b) => b.addEventListener("click", () => { c.value = t.value = b.dataset.color; cf.closest("form").dispatchEvent(new Event("cms:dirty")); }));
  });

  /* ---------------- منتقي الأيقونات ---------------- */
  $$(".icon-picker").forEach((ip) => {
    const btn = $(".icon-picker-btn", ip), hidden = $("input[type=hidden]", ip), search = $(".icon-search", ip);
    const setIcon = (v) => {
      hidden.value = v;
      $(".ip-preview i", ip).className = "fa-solid " + v;
      $("code", ip).textContent = v;
      $$(".icon-grid button", ip).forEach((b) => b.classList.toggle("sel", b.dataset.icon === v));
      ip.closest("form") && ip.closest("form").dispatchEvent(new Event("cms:dirty"));
    };
    btn.addEventListener("click", (e) => { e.preventDefault(); $$(".icon-picker.open").forEach((o) => o !== ip && o.classList.remove("open")); ip.classList.toggle("open"); search && search.focus(); });
    $$(".icon-grid button", ip).forEach((b) => b.addEventListener("click", (e) => { e.preventDefault(); setIcon(b.dataset.icon); ip.classList.remove("open"); }));
    search && search.addEventListener("input", () => {
      const q = search.value.trim().toLowerCase().replace(/^fa-/, "");
      $$(".icon-grid button", ip).forEach((b) => (b.style.display = b.dataset.icon.includes(q) ? "" : "none"));
    });
    search && search.addEventListener("keydown", (e) => {
      if (e.key === "Enter") { e.preventDefault(); const v = search.value.trim(); if (v) setIcon(v.startsWith("fa-") ? v : "fa-" + v); ip.classList.remove("open"); }
    });
  });
  document.addEventListener("click", (e) => { if (!e.target.closest(".icon-picker")) $$(".icon-picker.open").forEach((o) => o.classList.remove("open")); });

  /* ---------------- إظهار/إخفاء أقسام الصفحة ---------------- */
  $$("[data-section-toggle]").forEach((cb) => {
    const card = cb.closest(".section-card");
    const link = $(`.editor-index a[href="#${card.id}"]`);
    const upd = () => { card.classList.toggle("is-off", !cb.checked); link && link.classList.toggle("off", !cb.checked); };
    cb.addEventListener("change", upd); upd();
  });

  /* ---------------- فهرس الأقسام (Scroll spy) ---------------- */
  const idx = $$(".editor-index a[href^='#']");
  if (idx.length && "IntersectionObserver" in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach((en) => {
        if (en.isIntersecting) idx.forEach((a) => a.classList.toggle("current", a.getAttribute("href") === "#" + en.target.id));
      });
    }, { rootMargin: "-30% 0px -60% 0px" });
    idx.forEach((a) => { const t = $(a.getAttribute("href")); t && io.observe(t); });
  }

  /* ---------------- إظهار/إخفاء العناصر (AJAX) ---------------- */
  $$("[data-toggle-url]").forEach((cb) => {
    cb.addEventListener("change", async () => {
      const holder = cb.closest(".item-card, .row-item");
      try {
        const r = await fetch(cb.dataset.toggleUrl, { method: "POST", headers: { "X-CSRF-TOKEN": csrf(), Accept: "application/json" } });
        const j = await r.json();
        if (!r.ok) throw 0;
        holder && holder.classList.toggle("inactive", !j.active);
        toast(j.active ? "أصبح العنصر ظاهرًا في الموقع" : "تم إخفاء العنصر من الموقع");
      } catch (e) { cb.checked = !cb.checked; toast("تعذر الحفظ، حاول مرة أخرى", "error"); }
    });
  });

  /* ---------------- إعادة الترتيب بالسحب (AJAX) ---------------- */
  $$("[data-sortable-url]").forEach((list) => {
    if (!window.Sortable) return;
    Sortable.create(list, {
      handle: ".item-handle, .row-handle", animation: 220, ghostClass: "sortable-ghost", chosenClass: "sortable-chosen",
      onEnd: async () => {
        const ids = $$("[data-id]", list).map((el) => el.dataset.id);
        $$(".item-order", list).forEach((o, i) => (o.textContent = i + 1));
        try {
          const r = await fetch(list.dataset.sortableUrl, { method: "POST", headers: { "X-CSRF-TOKEN": csrf(), "Content-Type": "application/json", Accept: "application/json" }, body: JSON.stringify({ ids }) });
          if (!r.ok) throw 0;
          toast("تم حفظ الترتيب الجديد");
        } catch (e) { toast("تعذر حفظ الترتيب", "error"); }
      },
    });
  });

  /* ---------------- البحث السريع داخل القوائم ---------------- */
  $$("[data-filter]").forEach((inp) => {
    const target = $(inp.dataset.filter);
    inp.addEventListener("input", () => {
      const q = inp.value.trim();
      $$("[data-search]", target).forEach((el) => (el.style.display = el.dataset.search.includes(q) ? "" : "none"));
    });
  });

  /* ---------------- إظهار كلمة المرور ---------------- */
  $$(".pass-toggle").forEach((b) => b.addEventListener("click", () => {
    const i = b.parentElement.querySelector("input");
    i.type = i.type === "password" ? "text" : "password";
    b.innerHTML = `<i class="fa-solid ${i.type === "password" ? "fa-eye" : "fa-eye-slash"}"></i>`;
  }));

  /* ---------------- لوحة الأوامر (Ctrl + K) ---------------- */
  const pal = $("#palette");
  if (pal && window.PALETTE) {
    const input = $("input", pal), list = $(".palette-list", pal);
    let sel = 0, items = [];
    const norm = (s) => s.replace(/[أإآ]/g, "ا").replace(/ة/g, "ه").replace(/ى/g, "ي").toLowerCase();
    const render = () => {
      const q = norm(input.value.trim());
      items = window.PALETTE.filter((p) => !q || norm(p.t + " " + (p.g || "") + " " + (p.k || "")).includes(q));
      sel = Math.min(sel, Math.max(0, items.length - 1));
      list.innerHTML = items.length ? "" : `<div class="palette-empty"><i class="fa-solid fa-face-meh"></i> لا توجد نتائج</div>`;
      items.forEach((p, i) => {
        const a = document.createElement("a");
        a.href = p.u; if (p.x) a.target = "_blank";
        a.className = i === sel ? "sel" : "";
        a.innerHTML = `<i class="fa-solid ${p.i}"></i><span></span><small></small>`;
        a.querySelector("span").textContent = p.t; a.querySelector("small").textContent = p.g || "";
        a.addEventListener("mouseenter", () => { sel = i; $$("a", list).forEach((x, j) => x.classList.toggle("sel", j === sel)); });
        list.appendChild(a);
      });
    };
    const open = () => { pal.classList.add("open"); input.value = ""; sel = 0; render(); setTimeout(() => input.focus(), 30); };
    const close = () => pal.classList.remove("open");
    $$("[data-palette]").forEach((b) => b.addEventListener("click", open));
    pal.addEventListener("click", (e) => { if (e.target === pal) close(); });
    input.addEventListener("input", () => { sel = 0; render(); });
    document.addEventListener("keydown", (e) => {
      if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === "k") { e.preventDefault(); pal.classList.contains("open") ? close() : open(); return; }
      if (!pal.classList.contains("open")) return;
      if (e.key === "Escape") close();
      if (e.key === "ArrowDown") { e.preventDefault(); sel = (sel + 1) % Math.max(1, items.length); render(); $(".sel", list) && $(".sel", list).scrollIntoView({ block: "nearest" }); }
      if (e.key === "ArrowUp") { e.preventDefault(); sel = (sel - 1 + items.length) % Math.max(1, items.length); render(); $(".sel", list) && $(".sel", list).scrollIntoView({ block: "nearest" }); }
      if (e.key === "Enter" && items[sel]) { e.preventDefault(); const it = items[sel]; it.x ? window.open(it.u, "_blank") : (location.href = it.u); }
    });
  }

  document.addEventListener("keydown", (e) => { if (e.key === "Escape") { modal && modal.classList.remove("open"); document.body.classList.remove("rail-open"); } });
})();
