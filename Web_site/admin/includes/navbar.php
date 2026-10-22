  <!-- Khung chứa thanh điều hướng ngang và nội dung chính -->
  <div class="wrapper">
      <header class="desktop-navbar">
          <div class="nav-left">
              <!-- Nút bật menu bên (Chỉ hiển thị trên thiết bị di động) -->
              <button class="top-menu-btn" id="mobileMenuBtn">
                  <i class="fa-solid fa-bars"></i>
              </button>
              <h2>Hệ thống quản trị ITMobile</h2>
          </div>
          <div class="nav-right">
              <!-- Khu vực thông tin người dùng đang đăng nhập -->
              <div class="user-profile">
                  <img src="<?php echo htmlspecialchars(!empty($_SESSION['avatar']) && file_exists('../uploads/avatar/' . $_SESSION['avatar']) ? '../uploads/avatar/' . $_SESSION['avatar'] : '../uploads/avatar/null.jpg'); ?>" alt="Avatar người dùng" class="user-avatar">
                  <div class="user-info">
                      <span class="user-name"><?php echo htmlspecialchars($_SESSION['full_name']); ?></span>
                      <span class="user-role">TK <?php echo htmlspecialchars($_SESSION['role']); ?></span>
                  </div>
              </div>
          </div>
      </header>