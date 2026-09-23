<?php
require_once '../../config.php';
$pageTitle = "Quản lý người dùng";

include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>
<main class="main-content">
    <div class="content-card">
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Người Dùng</h1>
                <p>Phân quyền, quản lý tài khoản và trạng thái hoạt động</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/page/nguoidung/addnguoidung.php" class="btn-primary" title="Thêm người dùng mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm người dùng mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-box">
                <input type="text" placeholder="Tìm kiếm theo tên, email, SĐT...">
            </div>
            <select class="filter-select">
                <option value="">-- Tất cả quyền --</option>
                <option value="admin">Admin (Quản trị viên)</option>
                <option value="customer">Khách hàng</option>
            </select>
            <select class="filter-select">
                <option value="">-- Trạng thái --</option>
                <option value="active">Hoạt động</option>
                <option value="locked">Đã khóa</option>
            </select>
            <button class="btn-filter">Lọc</button>
        </div>

        <!-- Bảng danh sách người dùng -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã tài khoản</th>
                        <th>Ảnh</th>
                        <th>Họ và tên</th>
                        <th>Email</th>
                        <th>Số điện thoại</th>
                        <th>Số đơn hàng</th>
                        <th>Quyền</th>
                        <th>Thao tác (Khóa / Mở)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>TK-01</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Nguyễn Văn An</span></td>
                        <td>an.nguyen@email.com</td>
                        <td>0901234567</td>
                        <td>12</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active">Mở khóa (Hoạt động)</option>
                                <option value="locked" selected>Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-02</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Trần Thị Bình</span></td>
                        <td>binh.tran@email.com</td>
                        <td>0912345678</td>
                        <td>3</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active" selected>Mở khóa (Hoạt động)</option>
                                <option value="locked">Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-03</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Lê Hoàng Nam</span></td>
                        <td>nam.le@email.com</td>
                        <td>0988887777</td>
                        <td>5</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active" selected>Mở khóa (Hoạt động)</option>
                                <option value="locked">Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-04</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Phạm Thị Mai</span></td>
                        <td>mai.pham@email.com</td>
                        <td>0933334444</td>
                        <td>0</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active">Mở khóa (Hoạt động)</option>
                                <option value="locked" selected>Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-05</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Hoàng Văn Hùng</span></td>
                        <td>hung.hoang@email.com</td>
                        <td>0977112233</td>
                        <td>8</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active" selected>Mở khóa (Hoạt động)</option>
                                <option value="locked">Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-06</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Vũ Thu Trang</span></td>
                        <td>trang.vu@email.com</td>
                        <td>0966554433</td>
                        <td>2</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active" selected>Mở khóa (Hoạt động)</option>
                                <option value="locked">Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>TK-07</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=100&auto=format&fit=crop&q=80" alt="Avatar" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Đặng Minh Tuấn</span></td>
                        <td>tuan.dang@email.com</td>
                        <td>0944556677</td>
                        <td>15</td>
                        <td>
                            <select class="role-select">
                                <option value="admin" selected>Admin</option>
                                <option value="customer">Khách hàng</option>
                            </select>
                        </td>
                        <td>
                            <select class="role-select status-action-select">
                                <option value="active">Mở khóa (Hoạt động)</option>
                                <option value="locked" selected>Khóa tài khoản</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php
include '../../includes/footer.php';
?>