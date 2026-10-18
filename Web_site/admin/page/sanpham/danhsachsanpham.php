<?php
require_once '../../config.php';
// Cấu hình tiêu đề động cho trang
$pageTitle = "Quản lý sản phẩm";

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
                <h1>Quản Lý Sản Phẩm</h1>
                <p>Thêm mới, cập nhật và quản lý danh mục điện thoại, phụ kiện</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/page/sanpham/themsanpham.php" class="btn-primary" title="Thêm sản phẩm mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm sản phẩm mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-box">
                <input type="text" placeholder="Tìm theo tên sản phẩm, mã SKU...">
            </div>
            <select class="filter-select">
                <option value="">-- Danh mục --</option>
                <option value="iphone">iPhone</option>
                <option value="samsung">Samsung Galaxy</option>
                <option value="xiaomi">Xiaomi</option>
                <option value="accessory">Phụ kiện</option>
            </select>
            <select class="filter-select">
                <option value="">-- Trạng thái kho --</option>
                <option value="instock">Còn hàng</option>
                <option value="lowstock">Sắp hết hàng</option>
                <option value="outstock">Hết hàng</option>
            </select>
            <button class="btn-filter">Lọc</button>
        </div>

        <!-- Bảng danh sách sản phẩm  -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã sản phẩm</th>
                        <th>Tên sản phẩm</th>
                        <th>Ảnh</th>
                        <th>Số lượng</th>
                        <th>Tình trạng</th>
                        <th>Giá tiền</th>
                        <th>Danh mục</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>SP-8821</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">iPhone 15 Pro Max 256GB</span></td>
                        <td><img src="https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=100&auto=format&fit=crop&q=80" alt="iPhone" class="product-img"></td>
                        <td>45</td>
                        <td><span class="badge-status status-green">Còn hàng</span></td>
                        <td><strong>29.990.000 đ</strong></td>
                        <td><span class="badge-category">iPhone</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8822</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Samsung Galaxy S24 Ultra</span></td>
                        <td><img src="https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=100&auto=format&fit=crop&q=80" alt="Samsung" class="product-img"></td>
                        <td>8</td>
                        <td><span class="badge-status status-yellow">Sắp hết</span></td>
                        <td><strong>26.990.000 đ</strong></td>
                        <td><span class="badge-category">Samsung</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8823</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Xiaomi 14 Pro 5G 12GB</span></td>
                        <td><img src="https://images.unsplash.com/photo-1598327105666-5b89351aff97?w=100&auto=format&fit=crop&q=80" alt="Xiaomi" class="product-img"></td>
                        <td>0</td>
                        <td><span class="badge-status status-red">Hết hàng</span></td>
                        <td><strong>18.500.000 đ</strong></td>
                        <td><span class="badge-category">Xiaomi</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8824</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Tai Nghe AirPods Pro 2 MagSafe</span></td>
                        <td><img src="https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=100&auto=format&fit=crop&q=80" alt="AirPods" class="product-img"></td>
                        <td>30</td>
                        <td><span class="badge-status status-green">Còn hàng</span></td>
                        <td><strong>5.990.000 đ</strong></td>
                        <td><span class="badge-category">Phụ kiện</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8825</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Sạc Nhanh Anker 20W USB-C</span></td>
                        <td><img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=100&auto=format&fit=crop&q=80" alt="Charger" class="product-img"></td>
                        <td>5</td>
                        <td><span class="badge-status status-yellow">Sắp hết</span></td>
                        <td><strong>350.000 đ</strong></td>
                        <td><span class="badge-category">Phụ kiện</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8826</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">MacBook Pro 16" M3 Max</span></td>
                        <td><img src="https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=100&auto=format&fit=crop&q=80" alt="MacBook" class="product-img"></td>
                        <td>12</td>
                        <td><span class="badge-status status-green">Còn hàng</span></td>
                        <td><strong>69.990.000 đ</strong></td>
                        <td><span class="badge-category">MacBook</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8827</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">iPad Pro 13" M4 (2024)</span></td>
                        <td><img src="https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=100&auto=format&fit=crop&q=80" alt="iPad" class="product-img"></td>
                        <td>3</td>
                        <td><span class="badge-status status-yellow">Sắp hết</span></td>
                        <td><strong>32.990.000 đ</strong></td>
                        <td><span class="badge-category">iPad</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8828</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Apple Watch Series 9 GPS 41mm</span></td>
                        <td><img src="https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=100&auto=format&fit=crop&q=80" alt="Watch" class="product-img"></td>
                        <td>19</td>
                        <td><span class="badge-status status-green">Còn hàng</span></td>
                        <td><strong>9.990.000 đ</strong></td>
                        <td><span class="badge-category">Phụ kiện</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8829</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Bàn Phím Magic Keyboard iPad</span></td>
                        <td><img src="https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=100&auto=format&fit=crop&q=80" alt="Keyboard" class="product-img"></td>
                        <td>0</td>
                        <td><span class="badge-status status-red">Hết hàng</span></td>
                        <td><strong>7.490.000 đ</strong></td>
                        <td><span class="badge-category">Phụ kiện</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>SP-8830</strong></td>
                        <td><span style="font-weight: 600; color: #0f172a;">Củ Sạc Nhanh Apple 67W USB-C</span></td>
                        <td><img src="https://images.unsplash.com/photo-1583863788434-e58a36330cf0?w=100&auto=format&fit=crop&q=80" alt="Adapter" class="product-img" style="width: 2px;"></td>
                        <td>25</td>
                        <td><span class="badge-status status-green">Còn hàng</span></td>
                        <td><strong>1.290.000 đ</strong></td>
                        <td><span class="badge-category">Phụ kiện</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/sanpham/capnhatsp.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="sản phẩm">
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