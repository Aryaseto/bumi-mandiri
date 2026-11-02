(function () {
  "use strict";

  // ===================== 1. TOGGLE MENU =====================
  const toggle = document.querySelector(".navbar__toggle");
  const menu = document.querySelector(".navbar__menu");

  if (toggle && menu) {
    toggle.addEventListener("click", function () {
      const isOpen = menu.classList.toggle("open");
      toggle.setAttribute("aria-expanded", isOpen);
      toggle.setAttribute("aria-label", isOpen ? "Tutup menu" : "Buka menu");
    });

    // Tutup menu saat link diklik (hanya pada layar kecil)
    menu.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        if (menu.classList.contains("open")) {
          menu.classList.remove("open");
          toggle.setAttribute("aria-expanded", "false");
          toggle.setAttribute("aria-label", "Buka menu");
        }
      });
    });
  }

  // ===================== 2. FORM KONTAK =====================
  const form = document.getElementById("formKontak");
  const status = document.getElementById("statusPesan");

  if (form && status) {
    form.addEventListener("submit", function (e) {
      e.preventDefault();

      const nama = form.querySelector('input[name="nama"]');
      const email = form.querySelector('input[name="email"]');
      const pesan = form.querySelector('textarea[name="pesan"]');

      // Validasi sederhana
      if (!nama.value.trim() || !email.value.trim() || !pesan.value.trim()) {
        showStatus("Semua kolom harus diisi.", true);
        return;
      }

      if (!isValidEmail(email.value)) {
        showStatus("Format email tidak valid.", true);
        return;
      }

      // Simulasi pengiriman (ganti dengan fetch/axios jika terhubung ke server)
      sendMessage();
    });
  }

  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  function sendMessage() {
    status.textContent = "Mengirim pesan...";
    status.className = "form__status";

    // Simulasi permintaan jaringan
    setTimeout(function () {
      status.textContent = "Pesan terkirim, terima kasih! 😊";
      status.className = "form__status form__status--sukses";
      form.reset();
    }, 1200);
  }

  function showStatus(text, isError) {
    status.textContent = text;
    status.className = "form__status" + (isError ? " form__status--error" : " form__status--sukses");
  }
})();
