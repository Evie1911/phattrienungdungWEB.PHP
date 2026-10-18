document.addEventListener("DOMContentLoaded", () => {
  const mobileMenuBtn = document.getElementById("mobileMenuBtn");
  const desktopToggleBtn = document.getElementById("desktopToggleBtn");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const body = document.body;

  // Kiểm tra an toàn: Nếu phần tử tồn tại thì mới gắn sự kiện
  if (mobileMenuBtn && sidebar && overlay) {
    mobileMenuBtn.addEventListener("click", () => {
      sidebar.classList.toggle("active");
      overlay.style.display = sidebar.classList.contains("active")
        ? "block"
        : "none";
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.remove("active");
      overlay.style.display = "none";
    });
  }

  if (desktopToggleBtn) {
    desktopToggleBtn.addEventListener("click", () => {
      body.classList.toggle("sidebar-collapsed");
    });
  }
});
