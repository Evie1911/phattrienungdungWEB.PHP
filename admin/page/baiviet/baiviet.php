<?php
require_once '../../config.php';
// Cấu hình tiêu đề động cho trang
$pageTitle = "Quản lý bài viết";

// Các thành phần giao diện
include '../../includes/header.php';
include '../../includes/sidebar.php';
include '../../includes/navbar.php';

// Cắt chuỗi giới hạn đúng $limit từ (mặc định là 5 chữ)
function limitWords($string, $limit = 5)
{
    $words = explode(" ", $string);
    if (count($words) > $limit) {
        return implode(" ", array_slice($words, 0, $limit)) . "...";
    }
    return $string;
}
?>
<main class="main-content">
    <div class="content-card">
        <!-- Tiêu đề trang & Nút hành động -->
        <div class="page-header">
            <div class="page-title">
                <h1>Quản Lý Bài Viết</h1>
                <p>Thêm mới, chỉnh sửa và quản lý nội dung bài viết, tin tức</p>
            </div>
            <a href="<?php echo BASE_URL; ?>/page/baiviet/thembaiviet.php" class="btn-primary" title="Thêm bài viết mới">
                <i class="fa-solid fa-plus"></i> <span>Thêm bài viết mới</span>
            </a>
        </div>

        <!-- Thanh tìm kiếm và bộ lọc -->
        <div class="filter-section">
            <div class="search-box">
                <input type="text" placeholder="Tìm theo tiêu đề bài viết, mã ID...">
            </div>
            <select class="filter-select">
                <option value="">-- Danh mục bài viết --</option>
                <option value="news">Tin công nghệ</option>
                <option value="review">Đánh giá bài viết</option>
                <option value="tutorial">Hướng dẫn - Thủ thuật</option>
                <option value="promotion">Khuyến mãi - Sự kiện</option>
            </select>
            <select class="filter-select">
                <option value="">-- Trạng thái --</option>
                <option value="published">Đã xuất bản</option>
                <option value="draft">Bản nháp</option>
                <option value="pending">Chờ duyệt</option>
            </select>
            <button class="btn-filter">Lọc</button>
        </div>

        <!-- Bảng danh sách bài viết -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Mã bài viết</th>
                        <th>Ảnh đại diện</th>
                        <th>Tiêu đề bài viết</th>
                        <th>Tác giả</th>
                        <th>Lượt xem</th>
                        <th>Danh mục</th>
                        <th>Trạng thái</th>
                        <th>Chức năng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>BV-101</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Đánh giá chi tiết iPhone 15 Pro Max: Đỉnh cao công nghệ Apple"><?php echo limitWords("Đánh giá chi tiết iPhone 15 Pro Max: Đỉnh cao công nghệ Apple", 5); ?></span></td>
                        <td>Nguyễn Văn An</td>
                        <td>1,245</td>
                        <td><span class="badge-category">Đánh giá</span></td>
                        <td><span class="badge-status status-green">Đã xuất bản</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-102</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Top 5 mẫu điện thoại chơi game tốt nhất năm 2026"><?php echo limitWords("Top 5 mẫu điện thoại chơi game tốt nhất năm 2026", 5); ?></span></td>
                        <td>Trần Thị Bình</td>
                        <td>856</td>
                        <td><span class="badge-category">Tin công nghệ</span></td>
                        <td><span class="badge-status status-green">Đã xuất bản</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-103</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Hướng dẫn cách tối ưu thời lượng pin cho Samsung Galaxy S24"><?php echo limitWords("Hướng dẫn cách tối ưu thời lượng pin cho Samsung Galaxy S24", 5); ?></span></td>
                        <td>Lê Hoàng Nam</td>
                        <td>432</td>
                        <td><span class="badge-category">Thủ thuật</span></td>
                        <td><span class="badge-status status-yellow">Bản nháp</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-104</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1607613009820-a29f7bb81c04?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Sự kiện Tech Day 2026: Trải nghiệm hệ sinh thái thông minh mới"><?php echo limitWords("Sự kiện Tech Day 2026: Trải nghiệm hệ sinh thái thông minh mới", 5); ?></span></td>
                        <td>Phạm Thị Mai</td>
                        <td>0</td>
                        <td><span class="badge-category">Sự kiện</span></td>
                        <td><span class="badge-status status-red">Chờ duyệt</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-105</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Đánh giá tai nghe AirPods Pro 2: Chống ồn chủ động xuất sắc"><?php echo limitWords("Đánh giá tai nghe AirPods Pro 2: Chống ồn chủ động xuất sắc", 5); ?></span></td>
                        <td>Hoàng Văn Hùng</td>
                        <td>2,100</td>
                        <td><span class="badge-category">Đánh giá</span></td>
                        <td><span class="badge-status status-green">Đã xuất bản</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-106</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Tìm hiểu về lập trình web hiện đại với React và PHP"><?php echo limitWords("Tìm hiểu về lập trình web hiện đại với React và PHP", 5); ?></span></td>
                        <td>Vũ Minh Tuấn</td>
                        <td>610</td>
                        <td><span class="badge-category">Thủ thuật</span></td>
                        <td><span class="badge-status status-green">Đã xuất bản</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
                                    <i class="fa-regular fa-trash-can"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td><strong>BV-107</strong></td>
                        <td><img src="https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=100&auto=format&fit=crop&q=80" alt="Thumbnail" class="product-img"></td>
                        <td><span style="font-weight: 600; color: #0f172a;" title="Xu hướng công nghệ trí tuệ nhân tạo AI trong năm tới"><?php echo limitWords("Xu hướng công nghệ trí tuệ nhân tạo AI trong năm tới", 5); ?></span></td>
                        <td>Nguyễn Thị Hồng</td>
                        <td>95</td>
                        <td><span class="badge-category">Tin công nghệ</span></td>
                        <td><span class="badge-status status-yellow">Bản nháp</span></td>
                        <td>
                            <div class="action-buttons">
                                <a href="<?php echo BASE_URL; ?>/page/baiviet/capnhatbaiviet.php" class="btn-action btn-edit" title="Chỉnh sửa"><i class="fa-regular fa-pen-to-square"></i></a>
                                <button class="btn-action btn-delete nutxoa" title="Xóa" data-type="bài viết">
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