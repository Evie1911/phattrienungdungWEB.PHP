-- =====================================================
-- DATABASE: IT MOBILE (phiên bản đầy đủ: Website + Trang quản trị Admin)
-- Đồ án sinh viên | MySQL / MariaDB (XAMPP) | utf8mb4
-- Cách dùng: phpMyAdmin -> Import (file này đã tự DROP/CREATE database)
-- Mật khẩu của TẤT CẢ tài khoản mẫu: password
-- =====================================================

DROP DATABASE IF EXISTS it_mobile;
CREATE DATABASE it_mobile CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE it_mobile;

-- =====================================================
-- PHẦN 1: CẤU TRÚC BẢNG
-- =====================================================

-- 1. NGƯỜI DÙNG (Quản lý người dùng: mã TK-01, quyền Admin/Khách hàng, Khóa/Mở khóa)
CREATE TABLE users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_code   VARCHAR(20)  NOT NULL UNIQUE,            -- Mã tài khoản: TK-01
    full_name   VARCHAR(100) NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,                   -- lưu bằng password_hash() của PHP
    phone       VARCHAR(15),
    birthday    DATE DEFAULT NULL,
    avatar      VARCHAR(255) DEFAULT NULL,               -- Ảnh đại diện
    role        ENUM('admin','customer') NOT NULL DEFAULT 'customer',   -- Quyền hạn
    status      ENUM('active','locked')  NOT NULL DEFAULT 'active',     -- Hoạt động / Khóa
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. NHÀ CUNG CẤP (ô "Nhà cung cấp" trong form sản phẩm)
CREATE TABLE suppliers (
    id       INT AUTO_INCREMENT PRIMARY KEY,
    name     VARCHAR(150) NOT NULL,
    phone    VARCHAR(15),
    email    VARCHAR(100),
    address  VARCHAR(255)
) ENGINE=InnoDB;

-- 3. DANH MỤC (2 cấp: nhóm menu website -> danh mục con quản lý trong Admin)
--    Cấp 1 (parent_id NULL): Điện thoại, Máy tính bảng, Phụ kiện, Laptop
--    Cấp 2: iPhone, Samsung, Xiaomi, MacBook, iPad, Apple Watch...
CREATE TABLE categories (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    category_code  VARCHAR(20)  NOT NULL UNIQUE,         -- Mã danh mục: DM-01
    parent_id      INT DEFAULT NULL,
    name           VARCHAR(100) NOT NULL,
    slug           VARCHAR(100) NOT NULL UNIQUE,
    icon           VARCHAR(50),                          -- icon FontAwesome (menu website)
    image          VARCHAR(255),                         -- Hình ảnh danh mục
    description    TEXT,                                 -- Mô tả danh mục
    sort_order     INT DEFAULT 0,
    status         TINYINT(1) DEFAULT 1,
    FOREIGN KEY (parent_id) REFERENCES categories(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 4. THƯƠNG HIỆU (bộ lọc "Thương hiệu" ở website)
CREATE TABLE brands (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- 5. SẢN PHẨM
CREATE TABLE products (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    sku            VARCHAR(20)  NOT NULL UNIQUE,         -- Mã sản phẩm: SP-8821
    category_id    INT NOT NULL,
    brand_id       INT NOT NULL,
    supplier_id    INT DEFAULT NULL,
    name           VARCHAR(200) NOT NULL,
    description    TEXT,
    price          DECIMAL(12,0) NOT NULL,               -- Giá bán (niêm yết, hiển thị gạch ngang)
    sale_price     DECIMAL(12,0) DEFAULT NULL,           -- Giá giảm (NULL = không giảm)
    cost_price     DECIMAL(12,0) DEFAULT 0,              -- Giá nhập (để tính lợi nhuận)
    stock          INT NOT NULL DEFAULT 0,               -- Số lượng tồn
    condition_text VARCHAR(50) DEFAULT 'Mới 100%',       -- Tình trạng máy
    image          VARCHAR(255),
    is_new         TINYINT(1) DEFAULT 0,                 -- nhãn "MỚI"
    is_featured    TINYINT(1) DEFAULT 0,                 -- "Sản phẩm nổi bật"
    is_bestseller  TINYINT(1) DEFAULT 0,                 -- "Sản phẩm bán chạy"
    status         TINYINT(1) DEFAULT 1,                 -- 1: hiển thị, 0: ẩn
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id),
    FOREIGN KEY (brand_id)    REFERENCES brands(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL,
    INDEX idx_price (price),
    INDEX idx_name (name)
) ENGINE=InnoDB;

CREATE TABLE product_colors (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    product_id  INT NOT NULL,
    color_name  VARCHAR(50) NOT NULL,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE product_storages (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    product_id  INT NOT NULL,
    storage     VARCHAR(50) NOT NULL,
    extra_price DECIMAL(12,0) DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE product_specs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    product_id  INT NOT NULL,
    spec_name   VARCHAR(100) NOT NULL,
    spec_value  VARCHAR(255) NOT NULL,
    sort_order  INT DEFAULT 0,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 6. NHẬP KHO (lịch sử nhập hàng từ nhà cung cấp, làm số liệu "đầu vào")
CREATE TABLE stock_imports (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    product_id   INT NOT NULL,
    supplier_id  INT DEFAULT NULL,
    quantity     INT NOT NULL,
    import_price DECIMAL(12,0) NOT NULL,
    imported_at  DATE NOT NULL,
    note         VARCHAR(255),
    FOREIGN KEY (product_id)  REFERENCES products(id),
    FOREIGN KEY (supplier_id) REFERENCES suppliers(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 7. BÀI VIẾT (Quản lý bài viết + nút "Thêm danh mục bài viết")
CREATE TABLE news_categories (
    id    INT AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL UNIQUE,
    slug  VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB;

CREATE TABLE articles (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    article_code  VARCHAR(20)  NOT NULL UNIQUE,          -- Mã bài viết: BV-101
    category_id   INT NOT NULL,
    title         VARCHAR(255) NOT NULL,
    summary       TEXT,                                  -- Tóm tắt ngắn
    content       LONGTEXT,
    thumbnail     VARCHAR(255),                          -- Ảnh đại diện
    author        VARCHAR(100),                          -- Tác giả
    view_count    INT DEFAULT 0,                         -- Lượt xem
    status        ENUM('Xuất bản','Bản nháp','Chờ duyệt') NOT NULL DEFAULT 'Bản nháp',
    is_visible    TINYINT(1) DEFAULT 1,                  -- Hiển thị (Công khai) / Ẩn
    published_at  DATETIME,                              -- Ngày đăng
    FOREIGN KEY (category_id) REFERENCES news_categories(id)
) ENGINE=InnoDB;

-- 8. ĐƠN HÀNG (giỏ hàng lưu bằng $_SESSION của PHP nên không có bảng riêng)
CREATE TABLE orders (
    id               INT AUTO_INCREMENT PRIMARY KEY,
    order_code       VARCHAR(30) NOT NULL UNIQUE,        -- DH-9012 hoặc IT-20261018-8108
    user_id          INT DEFAULT NULL,                   -- NULL nếu khách vãng lai
    receiver_name    VARCHAR(100) NOT NULL,
    phone            VARCHAR(15)  NOT NULL,
    email            VARCHAR(100) NOT NULL,
    address          VARCHAR(255) NOT NULL,
    note             TEXT,
    payment_method   ENUM('COD','BANK_TRANSFER','VNPAY_QR','SANDBOX') NOT NULL DEFAULT 'COD',
    payment_status   ENUM('Chưa thanh toán','Chờ xác nhận','Đã thanh toán','Thất bại','Đã hoàn tiền','Đã hủy')
                     NOT NULL DEFAULT 'Chưa thanh toán',
    order_status     ENUM('Chờ xác nhận','Đã xác nhận','Đang đóng gói','Đang giao hàng','Hoàn thành','Đã hủy')
                     NOT NULL DEFAULT 'Chờ xác nhận',
    shipping_fee     DECIMAL(12,0) DEFAULT 0,
    total_amount     DECIMAL(12,0) NOT NULL,
    created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_order_status (order_status),
    INDEX idx_created (created_at)
) ENGINE=InnoDB;

CREATE TABLE order_items (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    order_id      INT NOT NULL,
    product_id    INT NOT NULL,
    product_name  VARCHAR(200) NOT NULL,                 -- lưu lại tên tại thời điểm mua
    color         VARCHAR(50),
    storage       VARCHAR(50),
    unit_price    DECIMAL(12,0) NOT NULL,
    cost_price    DECIMAL(12,0) DEFAULT 0,               -- giá vốn tại thời điểm mua
    quantity      INT NOT NULL DEFAULT 1,
    FOREIGN KEY (order_id)   REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
) ENGINE=InnoDB;

-- Lịch sử đổi trạng thái đơn hàng (tự ghi bằng trigger)
CREATE TABLE order_status_history (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    order_id    INT NOT NULL,
    old_status  VARCHAR(30),
    new_status  VARCHAR(30) NOT NULL,
    changed_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- 9. THANH TOÁN (Quản lý thanh toán: PAY-9825, COD, chuyển khoản, VNPay QR)
CREATE TABLE payments (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    payment_code   VARCHAR(20) NOT NULL UNIQUE,          -- Mã giao dịch: PAY-9825
    order_id       INT NOT NULL,
    method         ENUM('COD','BANK_TRANSFER','VNPAY_QR','SANDBOX') NOT NULL,
    amount         DECIMAL(12,0) NOT NULL,
    status         ENUM('Chờ xác nhận','COD - Chờ thu tiền','Thành công','Thất bại','Đã hoàn tiền','Đã hủy')
                   NOT NULL DEFAULT 'Chờ xác nhận',
    reference_code VARCHAR(60) DEFAULT NULL,             -- Mã đối soát / Ref: VCB-987654321
    note           VARCHAR(255),                         -- Ghi chú
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    confirmed_at   DATETIME DEFAULT NULL,
    confirmed_by   INT DEFAULT NULL,                     -- admin duyệt
    FOREIGN KEY (order_id)     REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (confirmed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- Yêu cầu hoàn tiền (popup "Yêu Cầu Hoàn Tiền": số tiền + lý do bắt buộc)
CREATE TABLE refunds (
    id             INT AUTO_INCREMENT PRIMARY KEY,
    payment_id     INT NOT NULL,
    refund_amount  DECIMAL(12,0) NOT NULL,
    reason         VARCHAR(255) NOT NULL,
    requested_by   INT DEFAULT NULL,
    created_at     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id)   REFERENCES payments(id) ON DELETE CASCADE,
    FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- 10. CÀI ĐẶT HỆ THỐNG (menu "Cài đặt hệ thống" + thông tin footer website)
CREATE TABLE settings (
    setting_key    VARCHAR(50) PRIMARY KEY,
    setting_value  VARCHAR(255) NOT NULL,
    description    VARCHAR(255)
) ENGINE=InnoDB;

-- 11. NHẬT KÝ HOẠT ĐỘNG CỦA ADMIN
CREATE TABLE admin_logs (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT DEFAULT NULL,
    action      VARCHAR(100) NOT NULL,                   -- vd: Khóa tài khoản, Duyệt chuyển khoản
    target      VARCHAR(100),                            -- vd: TK-04, PAY-9824
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- =====================================================
-- PHẦN 2: DỮ LIỆU MẪU
-- =====================================================

-- Người dùng: id 1 = admin đăng nhập, 2..7 = TK-01..TK-06, 8 = khách đặt hàng trên website
INSERT INTO users (id, user_code, full_name, email, password, phone, birthday, avatar, role, status) VALUES
(1, 'TK-00', 'Nguyễn Văn Admin',  'admin@itmobile.vn',            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0900000000', '1990-01-15', 'admin.jpg', 'admin',    'active'),
(2, 'TK-01', 'Nguyễn Văn An',     'an.nguyen@email.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', '1992-07-21', 'tk01.jpg',  'admin',    'active'),
(3, 'TK-02', 'Trần Thị Bình',     'binh.tran@email.com',          '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', '1995-04-30', 'tk02.jpg',  'customer', 'active'),
(4, 'TK-03', 'Lê Hoàng Nam',      'nam.le@email.com',             '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0988887777', '1999-03-12', 'tk03.jpg',  'customer', 'active'),
(5, 'TK-04', 'Phạm Thị Mai',      'mai.pham@email.com',           '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0933334444', '2000-12-04', 'tk04.jpg',  'customer', 'locked'),
(6, 'TK-05', 'Hoàng Văn Hùng',    'hung.hoang@email.com',         '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0977112233', '1988-09-09', 'tk05.jpg',  'customer', 'active'),
(7, 'TK-06', 'Vũ Thu Trang',      'trang.vu@email.com',           '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0966554433', '1997-06-18', 'tk06.jpg',  'customer', 'active'),
(8, 'TK-07', 'Y Duong',           'duongngocnhuy.work@gmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0774166232', '2003-02-02', NULL,        'customer', 'active');

INSERT INTO suppliers (id, name, phone, email, address) VALUES
(1, 'Apple Authorized',          '1800 1127', 'contact@apple-authorized.vn', 'TP. Hồ Chí Minh'),
(2, 'Samsung Vietnam',           '1800 588889','b2b@samsung.vn',              'Bắc Ninh'),
(3, 'Xiaomi Vietnam Distribution','0909 111 222','sales@xiaomi-vn.com',       'Hà Nội'),
(4, 'OPPO Vietnam',              '1800 577776','b2b@oppo.vn',                 'Hà Nội'),
(5, 'Digiworld Phụ kiện',        '0909 333 444','order@digiworld.vn',         'TP. Hồ Chí Minh'),
(6, 'Sony Electronics Vietnam',  '1800 588885','b2b@sony.vn',                 'Hà Nội');

-- Danh mục: cấp 1 (nhóm menu website) và cấp 2 (danh mục trong Admin)
INSERT INTO categories (id, category_code, parent_id, name, slug, icon, image, description, sort_order) VALUES
(1,  'NM-01', NULL, 'Điện thoại',    'dien-thoai',    'fa-mobile-screen',        'dm4.jpg', 'Nhóm điện thoại thông minh', 1),
(2,  'NM-02', NULL, 'Máy tính bảng', 'may-tinh-bang', 'fa-tablet-screen-button', 'dm3.jpg', 'Nhóm máy tính bảng',        2),
(3,  'NM-03', NULL, 'Phụ kiện',      'phu-kien',      'fa-headphones',           'dm2.png', 'Nhóm phụ kiện công nghệ',   3),
(4,  'NM-04', NULL, 'Laptop',        'laptop',        'fa-laptop',               'DM1.webp', 'Nhóm máy tính xách tay',    4);INSERT INTO brands (id, name) VALUES
(1,'Apple'),(2,'Samsung'),(3,'Xiaomi'),(4,'OPPO'),(5,'Anker'),(6,'Baseus'),(7,'UAG'),(8,'Sony');

-- Sản phẩm (price = giá bán niêm yết, sale_price = giá giảm)
INSERT INTO products
(id, sku, category_id, brand_id, supplier_id, name, description, price, sale_price, stock, image, is_new, is_featured, is_bestseller) VALUES
(1,'SP-8821',1,1,1,'iPhone 16 Pro Max 256GB', 'iPhone 16 Pro Max trang bị chip A18 Pro mạnh mẽ vượt trội...', 34990000, 32990000, 25, 'iphone-16-pro-max.jpg', 1,1,1),
(2,'SP-8822',1,2,2,'Samsung Galaxy S24 Ultra 5G', 'Flagship Android với bút S Pen, camera 200MP...', 33990000, 27990000, 30, 'galaxy-s24-ultra.jpg', 0,1,1),
(3,'SP-8823',1,3,3,'Xiaomi 14 Ultra 5G', 'Camera hợp tác Leica chuyên nghiệp, chip Snapdragon 8 Gen 3...', 32990000, 26990000, 20, 'xiaomi-14-ultra.jpg', 1,1,0),
(4,'SP-8824',1,4,4,'OPPO Find X7 Ultra', 'Hệ thống camera kép tiềm vọng Hasselblad, màn hình LTPO...', 25990000, 22990000, 18, 'oppo-find-x7-ultra.jpg', 1,1,0),
(5,'SP-8825',2,1,1,'iPad Pro 13 inch M4 Wi-Fi 256GB', 'iPad Pro mỏng nhẹ kỷ lục trang bị chip Apple M4...', 37990000, 35990000, 15, 'ipad-pro-m4.jpg', 1,1,1),
(6,'SP-8826',2,2,2,'Samsung Galaxy Tab S9 Ultra', 'Máy tính bảng Android cao cấp màn hình AMOLED 14.6 inch...', 30990000, 24990000, 8, 'galaxy-tab-s9-ultra.jpg', 0,0,0),
(7,'SP-8827',2,3,3,'Xiaomi Pad 6S Pro 12.4', 'Máy tính bảng màn hình 12.4 inch 144Hz, chip Snapdragon 8 Gen 2...', 14990000, 12990000, 22, 'xiaomi-pad-6s-pro.jpg', 1,0,1),
(8,'SP-8828',3,1,1,'Tai nghe Apple AirPods Pro 2 USB-C', 'Tai nghe chống ồn chủ động ANC gấp 2 lần...', 6190000, 5490000, 40, 'airpods-pro-2.jpg', 0,1,1),
(13,'SP-8833',4,1,1,'MacBook Pro 16 inch M3 Max', 'MacBook Pro 16 inch chip M3 Max, màn hình Liquid Retina...', 72990000, 69990000, 12, 'macbook-pro-16.jpg', 0,0,0),
(15,'SP-8835',3,1,1,'Apple Watch Series 10 46mm', 'Đồng hồ thông minh Apple Watch Series 10 màn hình lớn...', 11990000, 10990000, 22, 'apple-watch-s10.jpg', 1,0,0),
(16,'SP-8836',1,3,3,'Xiaomi 14 Pro 5G 12GB', 'Xiaomi 14 Pro RAM 12GB, camera Leica, sạc nhanh 120W...', 19990000, 18500000, 0, 'xiaomi-14-pro.jpg', 0,0,0);

-- Giá nhập mẫu = 85% giá bán thực tế (làm tròn đến chục nghìn)
UPDATE products SET cost_price = ROUND(COALESCE(sale_price, price) * 0.85, -4);

INSERT INTO product_colors (product_id, color_name) VALUES
(1,'Titan Tự Nhiên'),(1,'Titan Sa Mạc'),(1,'Titan Trắng'),(1,'Titan Đen'),
(2,'Xám Titan'),(2,'Đen Titan'),(2,'Tím Titan'),
(3,'Đen'),(3,'Trắng'),
(4,'Nâu'),(4,'Xanh'),
(5,'Bạc (Silver)'),(5,'Đen Không Gian (Space Black)'),
(6,'Xám'),(6,'Be'),
(7,'Xanh'),(7,'Xám'),
(8,'Trắng'),
(13,'Bạc'),(13,'Đen Không Gian'),
(15,'Đen Jet Black'),(15,'Bạc'),
(16,'Đen'),(16,'Xanh');

INSERT INTO product_storages (product_id, storage, extra_price) VALUES
(1,'256GB',0),(1,'512GB',5000000),(1,'1TB',10000000),
(2,'256GB',0),(2,'512GB',3000000),
(3,'256GB',0),(3,'512GB',3000000),
(4,'256GB',0),(4,'512GB',3000000),
(5,'256GB',0),(5,'512GB',5000000),(5,'1TB',15000000),
(6,'256GB',0),(6,'512GB',4000000),
(7,'256GB',0),
(8,'Tiêu chuẩn',0),
(13,'1TB',0),(13,'2TB',15000000),
(15,'46mm',0),
(16,'512GB',0);

INSERT INTO product_specs (product_id, spec_name, spec_value, sort_order) VALUES
(1,'Màn hình','6.9 inch Super Retina XDR OLED 120Hz',1),
(1,'Chip xử lý','Apple A18 Pro 3nm',2),
(1,'RAM','8 GB',3),
(1,'Dung lượng','256 GB',4),
(1,'Camera sau','Chính 48MP, Góc siêu rộng 48MP, Tele 12MP (5x)',5),
(1,'Camera trước','12 MP TrueDepth',6),
(1,'Pin & Sạc','4685 mAh, Sạc nhanh 30W, MagSafe 25W',7),
(5,'Màn hình','13.0 inch Ultra Retina XDR OLED ProMotion 120Hz',1),
(5,'Chip xử lý','Apple M4 9-core/10-core',2),
(5,'RAM','8 GB',3),
(5,'Dung lượng','256 GB',4),
(5,'Camera sau','12 MP f/1.8 + Cảm biến LiDar',5),
(5,'Camera trước','12 MP Ultra Wide ngang',6),
(5,'Pin & Sạc','38.99 Wh, Sạc nhanh 30W',7),
(8,'Chip audio','Apple H2',1),
(8,'Kết nối','Bluetooth 5.3',2),
(8,'Thời lượng pin','6 giờ liên tục (30 giờ kèm hộp sạc)',3),
(8,'Kháng nước','IP54',4),
(8,'Cổng sạc','USB-C & MagSafe không dây',5),
(13,'Màn hình','16.2 inch Liquid Retina XDR',1),
(13,'Chip xử lý','Apple M3 Max 14-core CPU',2),
(13,'RAM','36 GB',3),
(13,'Dung lượng','1 TB SSD',4);

-- Nhập kho mẫu
INSERT INTO stock_imports (product_id, supplier_id, quantity, import_price, imported_at, note) VALUES
(1, 1, 30, 28000000, '2026-05-05', 'Nhập lô đầu iPhone 16 Pro Max'),
(2, 2, 40, 23800000, '2026-05-10', 'Nhập Galaxy S24 Ultra'),
(5, 1, 20, 30600000, '2026-06-15', 'Nhập iPad Pro M4'),
(8, 1, 50, 4670000,  '2026-07-01', 'Nhập AirPods Pro 2'),
(13,1, 15, 59500000, '2026-09-03', 'Nhập MacBook Pro 16 M3 Max');

-- Danh mục bài viết + Bài viết
INSERT INTO news_categories (id, name, slug) VALUES
(1,'Đánh giá sản phẩm','danh-gia'),
(2,'Tin công nghệ','tin-cong-nghe'),
(3,'Thủ thuật','thu-thuat'),
(4,'Sự kiện','su-kien');

INSERT INTO articles (article_code, category_id, title, summary, thumbnail, author, view_count, status, is_visible, published_at) VALUES
('BV-101',1,'Đánh giá chi tiết iPhone 16 Pro Max: Nút Camera Control có thực sự hữu ích?',
 'Khám phá trải nghiệm thực tế iPhone 16 Pro Max với nút phím cơ camera hoàn toàn mới, viền màn hình siêu mỏng và thời lượng pin ấn tượng.',
 'news-iphone16.jpg','Nguyễn Văn An',1245,'Xuất bản',1,'2026-10-06 08:30:00'),
('BV-102',2,'Top 5 điện thoại tầm giá 15 - 20 triệu đáng mua nhất năm 2026',
 'Tổng hợp những mẫu smartphone cao cấp sở hữu cấu hình mạnh, camera đẹp và thiết kế sang trọng trong phân khúc tiệm cận flagship.',
 'news-top5.jpg','Trần Thị Bình',856,'Xuất bản',1,'2026-08-25 09:00:00'),
('BV-103',3,'Hướng dẫn cách tối ưu hiệu năng điện thoại Android khi chơi game',
 'Các bước tinh chỉnh cài đặt giúp máy mượt hơn, mát hơn khi chơi game nặng.',
 'news-game.jpg','Lê Hoàng Nam',432,'Bản nháp',0,NULL),
('BV-104',4,'Sự kiện Tech Day 2026: Trải nghiệm công nghệ mới nhất cùng IT Mobile',
 'Chương trình trải nghiệm sản phẩm mới, ưu đãi độc quyền dành cho khách hàng tham dự.',
 'news-techday.jpg','Phạm Thị Mai',0,'Chờ duyệt',0,NULL),
('BV-105',1,'Đánh giá tai nghe AirPods Pro 2 USB-C: Chống ồn đỉnh cao',
 'Trải nghiệm thực tế khả năng chống ồn chủ động và chất âm của AirPods Pro 2 USB-C.',
 'news-airpods.jpg','Hoàng Văn Hùng',2100,'Xuất bản',1,'2026-09-12 10:00:00'),
('BV-106',3,'Tìm hiểu về lập trình ứng dụng di động cho người mới bắt đầu',
 'Lộ trình học lập trình mobile cơ bản dành cho sinh viên công nghệ thông tin.',
 'news-code.jpg','Vũ Minh Tuấn',610,'Xuất bản',1,'2026-09-01 14:00:00'),
('BV-107',3,'Hướng dẫn 8 mẹo tiết kiệm pin cực hiệu quả cho điện thoại Samsung Galaxy AI',
 'Bí quyết giúp kéo dài thời gian sử dụng trên Galaxy S24 Series và các thiết bị One UI 6.1 bằng cách tối ưu hóa các tính năng trí tuệ nhân tạo.',
 'news-pin.jpg','Lê Hoàng Nam',780,'Xuất bản',1,'2026-07-18 08:00:00'),
('BV-108',3,'Cách phân biệt củ sạc Anker & Baseus chính hãng tránh mua phải hàng giả',
 'Tránh nguy cơ cháy nổ thiết bị với các dấu hiệu nhận biết sạc cáp thật - giả chi tiết qua tem mã cào QR và độ hoàn thiện cơ khí.',
 'news-sac.jpg','Nguyễn Văn An',540,'Xuất bản',1,'2026-06-05 08:00:00');

-- Đơn hàng (id 1 = đơn đặt trên website, id 2..10 = đơn trong trang quản trị)
INSERT INTO orders
(id, order_code, user_id, receiver_name, phone, email, address, note,
 payment_method, payment_status, order_status, shipping_fee, total_amount, created_at) VALUES
(1, 'IT-20261018-8108', 8, 'Y Duong',        '0774166232', 'duongngocnhuy.work@gmail.com', 'TPHCM', NULL,
    'SANDBOX','Đã thanh toán','Đã xác nhận',0, 65980000, '2026-10-18 18:20:00'),
(2, 'DH-9012', 2, 'Nguyễn Văn An',   '0901234567', 'an.nguyen@email.com',   'TPHCM', NULL,
    'VNPAY_QR','Đã thanh toán','Đã xác nhận',0, 32990000, '2026-09-19 10:15:00'),
(3, 'DH-9013', 3, 'Trần Thị Bình',   '0912345678', 'binh.tran@email.com',   'Hà Nội','Giao giờ hành chính',
    'BANK_TRANSFER','Chờ xác nhận','Chờ xác nhận',0, 27990000, '2026-09-19 09:40:00'),
(4, 'DH-9014', 4, 'Lê Hoàng Nam',    '0988887777', 'nam.le@email.com',      'Đà Nẵng',NULL,
    'COD','Chưa thanh toán','Đang đóng gói',0, 10980000, '2026-09-19 11:20:00'),
(5, 'DH-9015', 6, 'Hoàng Văn Hùng',  '0977112233', 'hung.hoang@email.com',  'Cần Thơ',NULL,
    'COD','Chưa thanh toán','Đang giao hàng',0, 36480000, '2026-09-18 18:05:00'),
(6, 'DH-9016', 7, 'Vũ Thu Trang',    '0966554433', 'trang.vu@email.com',    'Hải Phòng',NULL,
    'VNPAY_QR','Đã thanh toán','Hoàn thành',0, 4180000,  '2026-08-10 15:30:00'),
(7, 'DH-9017', 3, 'Trần Thị Bình',   '0912345678', 'binh.tran@email.com',   'Hà Nội',NULL,
    'COD','Đã thanh toán','Hoàn thành',0, 12990000, '2026-07-15 09:10:00'),
(8, 'DH-9018', 4, 'Lê Hoàng Nam',    '0988887777', 'nam.le@email.com',      'Đà Nẵng',NULL,
    'BANK_TRANSFER','Đã thanh toán','Hoàn thành',0, 22990000, '2026-06-20 13:45:00'),
(9, 'DH-9019', 6, 'Hoàng Văn Hùng',  '0977112233', 'hung.hoang@email.com',  'Cần Thơ','Khách đổi ý',
    'COD','Đã hủy','Đã hủy',0, 7490000,  '2026-05-30 16:00:00'),
(10,'DH-9020', 2, 'Nguyễn Văn An',   '0901234567', 'an.nguyen@email.com',   'TPHCM', NULL,
    'COD','Đã thanh toán','Hoàn thành',0, 24990000, '2026-05-12 10:00:00');

INSERT INTO order_items (order_id, product_id, product_name, color, storage, unit_price, quantity) VALUES
(1, 1,  'iPhone 16 Pro Max 256GB',                 'Titan Tự Nhiên', '256GB', 32990000, 2),
(2, 1,  'iPhone 16 Pro Max 256GB',                 'Titan Đen',      '256GB', 32990000, 1),
(3, 2,  'Samsung Galaxy S24 Ultra 5G',             'Xám Titan',      '256GB', 27990000, 1),
(4, 8,  'Tai nghe Apple AirPods Pro 2 USB-C',      'Trắng',          'Tiêu chuẩn', 5490000, 2),
(5, 5,  'iPad Pro 13 inch M4 Wi-Fi 256GB',         'Bạc (Silver)',   '256GB', 35990000, 1),
(6, 8, 'Tai nghe Apple AirPod', 'Trắng',          'Tiêu chuẩn', 4490000, 1),
(7, 7,  'Xiaomi Pad 6S Pro 12.4',                  'Xanh',           '256GB', 12990000, 1),
(8, 4,  'OPPO Find X7 Ultra',                      'Nâu',            '256GB', 22990000, 1),
(9, 1, 'iPhone 16 Pro Max 256GB', 'Đen', '512GB', 32990000, 1),
(10,6,  'Samsung Galaxy Tab S9 Ultra',             'Xám',            '256GB', 24990000, 1);

UPDATE order_items oi JOIN products p ON p.id = oi.product_id SET oi.cost_price = p.cost_price;

INSERT INTO payments
(payment_code, order_id, method, amount, status, reference_code, note, created_at, confirmed_at, confirmed_by) VALUES
('PAY-9830', 1,  'SANDBOX',       65980000, 'Thành công',        'SANDBOX-IT20261018-8108', 'Thanh toán mô phỏng Sandbox', '2026-10-18 18:21:00', '2026-10-18 18:21:00', NULL),
('PAY-9823', 2,  'VNPAY_QR',      32990000, 'Thành công',        'VNP-20260919-001',        NULL,                          '2026-09-19 10:15:00', '2026-09-19 10:16:00', NULL),
('PAY-9824', 3,  'BANK_TRANSFER', 27990000, 'Chờ xác nhận',      NULL,                      'Cần kiểm tra ngân hàng',      '2026-09-19 09:40:00', NULL, NULL),
('PAY-9825', 4,  'COD',           10980000, 'COD - Chờ thu tiền','COD-SHIPPER-442',         'Chờ giao hàng và thu tiền tại nhà','2026-09-19 11:20:00', NULL, NULL),
('PAY-9822', 5,  'COD',           36480000, 'COD - Chờ thu tiền','COD-SHIPPER-398',         'Chờ giao hàng và thu tiền tại nhà','2026-09-18 18:05:00', NULL, NULL),
('PAY-9826', 6,  'VNPAY_QR',      4180000,  'Thành công',        'VNP-20260810-014',        NULL,                          '2026-08-10 15:30:00', '2026-08-10 15:31:00', NULL),
('PAY-9827', 7,  'COD',           12990000, 'Thành công',        'COD-SHIPPER-201',         'Shipper đã thu tiền',         '2026-07-15 09:10:00', '2026-07-17 14:00:00', 1),
('PAY-9828', 8,  'BANK_TRANSFER', 22990000, 'Thành công',        'VCB-987654321',           'Đã đối soát Vietcombank',     '2026-06-20 13:45:00', '2026-06-20 15:00:00', 1),
('PAY-9829', 9,  'COD',           7490000,  'Đã hủy',            NULL,                      'Đơn bị hủy',                  '2026-05-30 16:00:00', NULL, NULL),
('PAY-9831', 10, 'COD',           24990000, 'Thành công',        'COD-SHIPPER-110',         'Shipper đã thu tiền',         '2026-05-12 10:00:00', '2026-05-15 11:00:00', 1);

INSERT INTO settings (setting_key, setting_value, description) VALUES
('store_name',     'IT Mobile',                               'Tên cửa hàng'),
('hotline',        '1900.6868',                               'Tổng đài mua hàng'),
('support_phone',  '0901.234.567',                            'Hỗ trợ kỹ thuật'),
('support_email',  'cskh@itmobile.vn',                        'Email chăm sóc khách hàng'),
('address',        '100 Nguyễn Trãi, Thanh Xuân, Hà Nội',     'Địa chỉ cửa hàng'),
('shipping_fee',   '0',                                       'Phí giao hàng mặc định (0 = miễn phí)'),
('warranty_months','12',                                      'Thời gian bảo hành (tháng)'),
('return_days',    '30',                                      'Số ngày đổi trả');

INSERT INTO admin_logs (user_id, action, target) VALUES
(1, 'Khóa tài khoản', 'TK-04'),
(1, 'Duyệt chuyển khoản', 'PAY-9828'),
(1, 'Cập nhật bài viết', 'BV-101');

-- =====================================================
-- PHẦN 3: VIEW (dùng trực tiếp trong PHP cho từng trang)
-- =====================================================

-- Trang website + Admin "Quản lý sản phẩm": giá cuối, % giảm, trạng thái kho
-- (Còn hàng: >10, Sắp hết: 1-10, Hết hàng: 0)
CREATE VIEW v_products AS
SELECT p.id, p.sku, p.name, p.description, p.price, p.sale_price,
       COALESCE(p.sale_price, p.price) AS final_price,
       IF(p.sale_price IS NOT NULL AND p.price > p.sale_price,
          ROUND((p.price - p.sale_price) / p.price * 100), 0) AS discount_percent,
       p.cost_price, p.stock, p.condition_text, p.image,
       p.is_new, p.is_featured, p.is_bestseller, p.status,
       CASE WHEN p.stock = 0 THEN 'Hết hàng'
            WHEN p.stock <= 10 THEN 'Sắp hết'
            ELSE 'Còn hàng' END AS stock_status,
       c.id AS category_id, c.name AS category_name,
       COALESCE(pc.name, c.name) AS group_name,
       COALESCE(pc.slug, c.slug) AS group_slug,
       b.name AS brand_name, s.name AS supplier_name
FROM products p
JOIN categories c      ON p.category_id = c.id
LEFT JOIN categories pc ON c.parent_id = pc.id
JOIN brands b          ON p.brand_id = b.id
LEFT JOIN suppliers s  ON p.supplier_id = s.id;

-- Admin "Quản lý người dùng": có cột Số đơn hàng
CREATE VIEW v_users_admin AS
SELECT u.id, u.user_code, u.avatar, u.full_name, u.email, u.phone, u.birthday,
       u.role, u.status, u.created_at,
       COUNT(o.id) AS order_count
FROM users u
LEFT JOIN orders o ON o.user_id = u.id
GROUP BY u.id;

-- Admin "Quản lý danh mục": có cột Số lượng sản phẩm
CREATE VIEW v_categories_admin AS
SELECT c.id, c.category_code, c.image, c.name, c.description,
       pc.name AS parent_name, c.status,
       COUNT(p.id) AS product_count
FROM categories c
LEFT JOIN categories pc ON c.parent_id = pc.id
LEFT JOIN products p    ON p.category_id = c.id
GROUP BY c.id;

-- Admin "Quản lý đơn hàng": tên sản phẩm gộp + tổng số lượng
CREATE VIEW v_orders_admin AS
SELECT o.id, o.order_code, o.receiver_name, o.phone, o.address,
       GROUP_CONCAT(oi.product_name SEPARATOR ', ') AS products_summary,
       SUM(oi.quantity) AS total_quantity,
       o.total_amount, o.payment_method, o.payment_status, o.order_status, o.created_at
FROM orders o
JOIN order_items oi ON oi.order_id = o.id
GROUP BY o.id;

-- Admin "Quản lý thanh toán": danh sách giao dịch
CREATE VIEW v_payments_admin AS
SELECT pay.id, pay.payment_code, o.order_code, o.receiver_name, o.phone,
       pay.method, pay.amount, pay.status, pay.reference_code, pay.note, pay.created_at
FROM payments pay
JOIN orders o ON o.id = pay.order_id;

-- 4 thẻ thống kê đầu trang "Quản lý thanh toán"
CREATE VIEW v_payment_stats AS
SELECT
  (SELECT COALESCE(SUM(amount),0) FROM payments WHERE status = 'Thành công')           AS total_revenue,
  (SELECT COUNT(*) FROM payments WHERE status = 'Thành công')                          AS success_count,
  (SELECT COUNT(*) FROM payments WHERE status = 'Chờ xác nhận')                        AS pending_count,
  (SELECT COUNT(*) FROM payments WHERE status = 'COD - Chờ thu tiền')                  AS cod_waiting_count;

-- 4 thẻ thống kê "Bảng điều khiển"
CREATE VIEW v_dashboard_stats AS
SELECT
  (SELECT COUNT(*) FROM users    WHERE role = 'customer')              AS total_customers,
  (SELECT COUNT(*) FROM products WHERE status = 1)                     AS total_products,
  (SELECT COUNT(*) FROM orders)                                        AS total_orders,
  (SELECT COUNT(*) FROM products WHERE stock BETWEEN 1 AND 10)         AS low_stock_products;

-- Biểu đồ "Thống kê doanh thu" (doanh thu + lợi nhuận theo tháng, bỏ đơn hủy)
CREATE VIEW v_monthly_revenue AS
SELECT DATE_FORMAT(o.created_at, '%Y-%m') AS month,
       SUM(oi.unit_price * oi.quantity)                    AS revenue,
       SUM((oi.unit_price - oi.cost_price) * oi.quantity)  AS profit
FROM orders o
JOIN order_items oi ON oi.order_id = o.id
WHERE o.payment_status = 'Đã thanh toán' AND o.order_status <> 'Đã hủy'
GROUP BY DATE_FORMAT(o.created_at, '%Y-%m');

-- Biểu đồ nhập kho theo tháng
CREATE VIEW v_monthly_imports AS
SELECT DATE_FORMAT(imported_at, '%Y-%m') AS month,
       SUM(quantity) AS total_quantity,
       SUM(quantity * import_price) AS total_cost
FROM stock_imports
GROUP BY DATE_FORMAT(imported_at, '%Y-%m');

-- =====================================================
-- PHẦN 4: STORED PROCEDURE (các popup trong trang thanh toán)
-- =====================================================
DELIMITER $$

-- Popup "Xác nhận duyệt chuyển khoản" / xác nhận đã thu tiền COD
CREATE PROCEDURE sp_confirm_payment(IN p_payment_id INT, IN p_reference VARCHAR(60), IN p_admin_id INT)
BEGIN
    DECLARE v_order_id INT;
    SELECT order_id INTO v_order_id FROM payments WHERE id = p_payment_id;
    UPDATE payments
       SET status = 'Thành công', reference_code = COALESCE(p_reference, reference_code),
           confirmed_at = NOW(), confirmed_by = p_admin_id
     WHERE id = p_payment_id;
    UPDATE orders SET payment_status = 'Đã thanh toán' WHERE id = v_order_id;
    INSERT INTO admin_logs (user_id, action, target)
    SELECT p_admin_id, 'Xác nhận thanh toán', payment_code FROM payments WHERE id = p_payment_id;
END$$

-- Popup "Yêu cầu hoàn tiền" (số tiền + lý do)
CREATE PROCEDURE sp_refund_payment(IN p_payment_id INT, IN p_amount DECIMAL(12,0), IN p_reason VARCHAR(255), IN p_admin_id INT)
BEGIN
    DECLARE v_order_id INT;
    SELECT order_id INTO v_order_id FROM payments WHERE id = p_payment_id;
    INSERT INTO refunds (payment_id, refund_amount, reason, requested_by)
    VALUES (p_payment_id, p_amount, p_reason, p_admin_id);
    UPDATE payments SET status = 'Đã hoàn tiền' WHERE id = p_payment_id;
    UPDATE orders   SET payment_status = 'Đã hoàn tiền' WHERE id = v_order_id;
    INSERT INTO admin_logs (user_id, action, target)
    SELECT p_admin_id, 'Hoàn tiền', payment_code FROM payments WHERE id = p_payment_id;
END$$

-- =====================================================
-- PHẦN 5: TRIGGER (tạo SAU khi nhập dữ liệu mẫu để không làm lệch tồn kho mẫu)
-- =====================================================

-- Đặt hàng -> tự trừ tồn kho
CREATE TRIGGER trg_order_items_after_insert
AFTER INSERT ON order_items
FOR EACH ROW
BEGIN
    UPDATE products SET stock = GREATEST(stock - NEW.quantity, 0) WHERE id = NEW.product_id;
END$$

-- Đổi trạng thái đơn -> ghi lịch sử; hủy đơn -> hoàn lại tồn kho
CREATE TRIGGER trg_orders_after_update
AFTER UPDATE ON orders
FOR EACH ROW
BEGIN
    IF OLD.order_status <> NEW.order_status THEN
        INSERT INTO order_status_history (order_id, old_status, new_status)
        VALUES (NEW.id, OLD.order_status, NEW.order_status);
        IF NEW.order_status = 'Đã hủy' THEN
            UPDATE products p
            JOIN order_items oi ON oi.product_id = p.id
            SET p.stock = p.stock + oi.quantity
            WHERE oi.order_id = NEW.id;
        END IF;
    END IF;
END$$

DELIMITER ;

-- =====================================================
-- PHẦN 6: TRUY VẤN MẪU CHO TỪNG TRANG
-- =====================================================
-- WEBSITE
-- SELECT * FROM v_products WHERE status=1 AND is_featured=1;                       -- Sản phẩm nổi bật
-- SELECT * FROM v_products WHERE status=1 AND is_bestseller=1;                     -- Sản phẩm bán chạy
-- SELECT * FROM v_products WHERE group_slug='dien-thoai' AND status=1;             -- Lọc theo nhóm menu
-- SELECT * FROM v_products WHERE final_price BETWEEN 10000000 AND 30000000 ORDER BY final_price;
-- SELECT * FROM articles WHERE status='Xuất bản' AND is_visible=1 ORDER BY published_at DESC LIMIT 4;
--
-- ADMIN
-- SELECT * FROM v_dashboard_stats;                                                 -- 4 thẻ thống kê
-- SELECT * FROM v_monthly_revenue ORDER BY month;                                  -- Biểu đồ doanh thu
-- SELECT * FROM v_users_admin WHERE role='customer' AND status='active';           -- Lọc người dùng
-- SELECT * FROM v_orders_admin WHERE order_status='Đang giao hàng';                -- Lọc đơn hàng
-- SELECT * FROM v_payment_stats;                                                   -- 4 thẻ trang thanh toán
-- CALL sp_confirm_payment(3, 'VCB-123456', 1);                                     -- Duyệt chuyển khoản PAY-9824
-- CALL sp_refund_payment(2, 1500000, 'Khách trả hàng', 1);                         -- Hoàn tiền
-- UPDATE users SET status='locked' WHERE user_code='TK-04';                        -- Khóa tài khoản
-- UPDATE orders SET order_status='Đã hủy' WHERE order_code='DH-9014';              -- Hủy đơn (tự hoàn kho)
