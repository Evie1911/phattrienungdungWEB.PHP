<?php
require_once '../../config.php';
// Cấu hình tiêu đề động cho trang
$pageTitle = "Quản lý danh mục";

//Các thành phần giao diện
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';
?>
<main class="main-content">
    <div class="content-card">
        <!-- Tiêu đề trang & Nút hành động -->
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Danh Mục</h1>
                <p>Thêm mới, cập nhật và quản lý hình ảnh, thông tin danh mục</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/page/danhmuc/themdanhmuc.php" class="btn-primary" title="Thêm danh mục mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm danh mục mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm -->
        <div class="filter-section">
            <div class="search-box" style="flex: 1;">
                <input type="text" placeholder="Tìm theo tên danh mục, mã...">
            </div>
            <button class="btn-filter">Tìm kiếm</button>
        </div>

        <!-- Bảng danh sách danh mục -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã danh mục</th>
                        <th>Hình ảnh</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Số lượng danh mục</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>DM-01</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=100&auto=format&fit=crop&q=80" alt="iPhone" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">iPhone</span></td>
                        <td>Các dòng điện thoại iPhone chính hãng Apple</td>
                        <td>45</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-02</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=100&auto=format&fit=crop&q=80" alt="Samsung" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Samsung</span></td>
                        <td>Điện thoại thông minh Samsung Galaxy các dòng</td>
                        <td>32</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-03</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=100&auto=format&fit=crop&q=80" alt="Xiaomi" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Xiaomi</span></td>
                        <td>Điện thoại và thiết bị công nghệ Xiaomi</td>
                        <td>18</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-04</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&auto=format&fit=crop&q=80" alt="MacBook" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">MacBook</span></td>
                        <td>Máy tính xách tay MacBook Air & Pro</td>
                        <td>12</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-05</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=100&auto=format&fit=crop&q=80" alt="iPad" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">iPad</span></td>
                        <td>Máy tính bảng Apple iPad các loại</td>
                        <td>15</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-06</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=100&auto=format&fit=crop&q=80" alt="Apple Watch" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Apple Watch</span></td>
                        <td>Đồng hồ thông minh Apple Watch các phiên bản</td>
                        <td>22</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-07</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=100&auto=format&fit=crop&q=80" alt="Tai nghe" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Tai nghe</span></td>
                        <td>Tai nghe Bluetooth, tai nghe có dây chính hãng</td>
                        <td>30</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-08</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=100&auto=format&fit=crop&q=80" alt="Sạc cáp" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Sạc & Cáp</span></td>
                        <td>Củ sạc nhanh, cáp sạc điện thoại và laptop</td>
                        <td>50</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-09</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=100&auto=format&fit=crop&q=80" alt="Bàn phím" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Bàn phím & Chuột</span></td>
                        <td>Phụ kiện bàn phím rời, chuột không dây văn phòng</td>
                        <td>14</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>DM-10</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1592945403244-b3fbafd7f539?w=100&auto=format&fit=crop&q=80" alt="Ốp lưng" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Ốp lưng & Bao da</span></td>
                        <td>Ốp lưng chống sốc, bao da bảo vệ thiết bị</td>
                        <td>85</td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/danhmuc/capnhatdm.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="danh mục">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
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