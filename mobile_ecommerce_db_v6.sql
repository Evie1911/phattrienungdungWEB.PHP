-- =====================================================================
-- DATABASE: Website Thương Mại Điện Tử Bán Thiết Bị Di Động
-- Chỉ có 2 ACTOR: admin (quản trị) và client (khách hàng)
-- -> Gộp chung 1 bảng "users", phân quyền bằng cột "role"
-- Chạy được trên phpMyAdmin - WampServer (MySQL/MariaDB, engine InnoDB)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS mobile_ecommerce
  CHARACTER SET utf8 COLLATE utf8_unicode_ci;

USE mobile_ecommerce;

SET FOREIGN_KEY_CHECKS = 0;

-- =====================================================================
-- 1. BẢNG TÀI KHOẢN (dùng chung cho cả admin và client)
--    role = 'admin'  -> quản trị viên (quản lý sản phẩm, đơn hàng, bài viết)
--    role = 'client' -> khách hàng (mua hàng, đánh giá, bình luận)
-- =====================================================================
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(15) DEFAULT NULL,
    address VARCHAR(255) DEFAULT NULL,
    avatar VARCHAR(255) DEFAULT NULL,
    gender ENUM('male','female','other') DEFAULT NULL,
    birthday DATE DEFAULT NULL,
    role ENUM('admin','client') NOT NULL DEFAULT 'client',
    status TINYINT(1) NOT NULL DEFAULT 1 COMMENT '1: hoạt động, 0: khóa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 2. BẢNG THƯƠNG HIỆU (Samsung, Apple, Xiaomi, Oppo...)
-- =====================================================================
DROP TABLE IF EXISTS brands;
CREATE TABLE brands (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    logo VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 3. BẢNG DANH MỤC SẢN PHẨM (tự tham chiếu để tạo danh mục cha - con)
-- =====================================================================
DROP TABLE IF EXISTS categories;
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT DEFAULT NULL,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    image VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    display_order INT NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_categories_parent FOREIGN KEY (parent_id)
        REFERENCES categories(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 4. BẢNG THUỘC TÍNH KỸ THUẬT (RAM, Bộ nhớ trong, Màn hình, Pin...)
-- =====================================================================
DROP TABLE IF EXISTS attributes;
CREATE TABLE attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    unit VARCHAR(20) DEFAULT NULL COMMENT 'Đơn vị: GB, inch, mAh...'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 5. BẢNG SẢN PHẨM
--    created_by: admin tạo sản phẩm (trỏ về users.id, role='admin')
-- =====================================================================
DROP TABLE IF EXISTS products;
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    brand_id INT NOT NULL,
    created_by INT DEFAULT NULL,
    name VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    sku VARCHAR(50) NOT NULL UNIQUE,
    description TEXT DEFAULT NULL,
    content LONGTEXT DEFAULT NULL COMMENT 'Mô tả chi tiết dạng HTML',
    price DECIMAL(15,0) NOT NULL DEFAULT 0,
    sale_price DECIMAL(15,0) DEFAULT NULL COMMENT 'Giá khuyến mãi (nếu có)',
    quantity INT NOT NULL DEFAULT 0 COMMENT 'Số lượng tồn kho',
    sold_count INT NOT NULL DEFAULT 0 COMMENT 'Số lượng đã bán',
    warranty_months INT DEFAULT 12,
    thumbnail VARCHAR(255) DEFAULT NULL,
    views INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('active','out_of_stock','hidden') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id)
        REFERENCES categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_products_brand FOREIGN KEY (brand_id)
        REFERENCES brands(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_products_creator FOREIGN KEY (created_by)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 6. BẢNG ẢNH SẢN PHẨM (nhiều ảnh / 1 sản phẩm)
-- =====================================================================
DROP TABLE IF EXISTS product_images;
CREATE TABLE product_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    is_main TINYINT(1) NOT NULL DEFAULT 0,
    display_order INT NOT NULL DEFAULT 0,
    CONSTRAINT fk_images_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 7. BẢNG BIẾN THỂ SẢN PHẨM (màu sắc, dung lượng)
-- =====================================================================
DROP TABLE IF EXISTS product_variants;
CREATE TABLE product_variants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    color VARCHAR(50) DEFAULT NULL,
    storage VARCHAR(50) DEFAULT NULL COMMENT 'Dung lượng bộ nhớ: 128GB, 256GB...',
    sku VARCHAR(60) NOT NULL UNIQUE,
    price DECIMAL(15,0) NOT NULL,
    sale_price DECIMAL(15,0) DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 0,
    image VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    CONSTRAINT fk_variants_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 8. BẢNG THÔNG SỐ KỸ THUẬT SẢN PHẨM (nhiều-nhiều: products <-> attributes)
-- =====================================================================
DROP TABLE IF EXISTS product_attributes;
CREATE TABLE product_attributes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    attribute_id INT NOT NULL,
    value VARCHAR(255) NOT NULL COMMENT 'Giá trị: 8GB, 6.7 inch, 5000mAh...',
    CONSTRAINT fk_pattr_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_pattr_attribute FOREIGN KEY (attribute_id)
        REFERENCES attributes(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_product_attribute (product_id, attribute_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 9. BẢNG GIỎ HÀNG (chỉ client mới có giỏ hàng)
-- =====================================================================
DROP TABLE IF EXISTS carts;
CREATE TABLE carts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_carts_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 10. BẢNG CHI TIẾT GIỎ HÀNG
-- =====================================================================
DROP TABLE IF EXISTS cart_items;
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15,0) NOT NULL COMMENT 'Giá tại thời điểm thêm vào giỏ',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_cartitems_cart FOREIGN KEY (cart_id)
        REFERENCES carts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cartitems_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_cartitems_variant FOREIGN KEY (variant_id)
        REFERENCES product_variants(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 11. BẢNG MÃ GIẢM GIÁ / VOUCHER (admin tạo, client sử dụng)
-- =====================================================================
DROP TABLE IF EXISTS vouchers;
CREATE TABLE vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_by INT DEFAULT NULL,
    code VARCHAR(50) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    discount_type ENUM('percent','amount') NOT NULL DEFAULT 'amount',
    discount_value DECIMAL(15,0) NOT NULL,
    min_order_value DECIMAL(15,0) NOT NULL DEFAULT 0,
    max_discount_value DECIMAL(15,0) DEFAULT NULL,
    quantity INT NOT NULL DEFAULT 0,
    used_count INT NOT NULL DEFAULT 0,
    start_date DATETIME NOT NULL,
    end_date DATETIME NOT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_vouchers_creator FOREIGN KEY (created_by)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 12. BẢNG ĐƠN HÀNG (client đặt hàng)
-- =====================================================================
DROP TABLE IF EXISTS orders;
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    order_code VARCHAR(30) NOT NULL UNIQUE,
    receiver_name VARCHAR(100) NOT NULL,
    receiver_phone VARCHAR(15) NOT NULL,
    receiver_email VARCHAR(100) DEFAULT NULL,
    shipping_address VARCHAR(255) NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    subtotal DECIMAL(15,0) NOT NULL DEFAULT 0,
    discount_amount DECIMAL(15,0) NOT NULL DEFAULT 0,
    shipping_fee DECIMAL(15,0) NOT NULL DEFAULT 0,
    total_amount DECIMAL(15,0) NOT NULL DEFAULT 0,
    payment_method ENUM('cod','bank_transfer','credit_card','e_wallet') NOT NULL DEFAULT 'cod',
    payment_status ENUM('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
    order_status ENUM('pending','confirmed','shipping','completed','cancelled') NOT NULL DEFAULT 'pending',
    updated_by INT DEFAULT NULL COMMENT 'Admin cập nhật trạng thái đơn hàng gần nhất',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_orders_updater FOREIGN KEY (updated_by)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 13. BẢNG CHI TIẾT ĐƠN HÀNG
-- =====================================================================
DROP TABLE IF EXISTS order_items;
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    variant_id INT DEFAULT NULL,
    product_name VARCHAR(200) NOT NULL COMMENT 'Lưu lại tên tại thời điểm mua',
    quantity INT NOT NULL DEFAULT 1,
    price DECIMAL(15,0) NOT NULL COMMENT 'Đơn giá tại thời điểm mua',
    subtotal DECIMAL(15,0) NOT NULL,
    CONSTRAINT fk_orderitems_order FOREIGN KEY (order_id)
        REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_orderitems_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_orderitems_variant FOREIGN KEY (variant_id)
        REFERENCES product_variants(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 14. BẢNG ÁP DỤNG VOUCHER CHO ĐƠN HÀNG (nhiều-nhiều)
-- =====================================================================
DROP TABLE IF EXISTS order_vouchers;
CREATE TABLE order_vouchers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    voucher_id INT NOT NULL,
    discount_amount DECIMAL(15,0) NOT NULL DEFAULT 0,
    CONSTRAINT fk_ordervouchers_order FOREIGN KEY (order_id)
        REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_ordervouchers_voucher FOREIGN KEY (voucher_id)
        REFERENCES vouchers(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    UNIQUE KEY uq_order_voucher (order_id, voucher_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 15. BẢNG THANH TOÁN
-- =====================================================================
DROP TABLE IF EXISTS payments;
CREATE TABLE payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    payment_method ENUM('cod','bank_transfer','credit_card','e_wallet') NOT NULL,
    transaction_code VARCHAR(100) DEFAULT NULL,
    amount DECIMAL(15,0) NOT NULL,
    status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
    paid_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id)
        REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 16. BẢNG LỊCH SỬ TRẠNG THÁI ĐƠN HÀNG (admin cập nhật, tracking cho client)
-- =====================================================================
DROP TABLE IF EXISTS order_status_history;
CREATE TABLE order_status_history (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    changed_by INT DEFAULT NULL COMMENT 'Admin thực hiện thay đổi',
    status ENUM('pending','confirmed','shipping','completed','cancelled') NOT NULL,
    note VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_statushistory_order FOREIGN KEY (order_id)
        REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_statushistory_user FOREIGN KEY (changed_by)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 17. BẢNG ĐÁNH GIÁ SẢN PHẨM (client đánh giá)
-- =====================================================================
DROP TABLE IF EXISTS reviews;
CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    user_id INT NOT NULL,
    order_item_id INT DEFAULT NULL COMMENT 'Liên kết đơn hàng để xác thực đã mua',
    rating TINYINT NOT NULL COMMENT 'Điểm đánh giá 1-5',
    comment TEXT DEFAULT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_reviews_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_reviews_orderitem FOREIGN KEY (order_item_id)
        REFERENCES order_items(id) ON DELETE SET NULL ON UPDATE CASCADE,
    CONSTRAINT chk_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 18. BẢNG SẢN PHẨM YÊU THÍCH (Wishlist - client)
-- =====================================================================
DROP TABLE IF EXISTS wishlists;
CREATE TABLE wishlists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_wishlists_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_wishlists_product FOREIGN KEY (product_id)
        REFERENCES products(id) ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE KEY uq_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 19. BẢNG DANH MỤC BÀI VIẾT
-- =====================================================================
DROP TABLE IF EXISTS post_categories;
CREATE TABLE post_categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    slug VARCHAR(120) NOT NULL UNIQUE,
    description VARCHAR(255) DEFAULT NULL,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 20. BẢNG BÀI VIẾT (admin đăng bài)
-- =====================================================================
DROP TABLE IF EXISTS posts;
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    author_id INT NOT NULL COMMENT 'Admin đăng bài (users.role = admin)',
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(250) NOT NULL UNIQUE,
    thumbnail VARCHAR(255) DEFAULT NULL,
    summary VARCHAR(500) DEFAULT NULL,
    content LONGTEXT NOT NULL,
    views INT NOT NULL DEFAULT 0,
    is_featured TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('draft','published','hidden') NOT NULL DEFAULT 'draft',
    published_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT fk_posts_category FOREIGN KEY (category_id)
        REFERENCES post_categories(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_posts_author FOREIGN KEY (author_id)
        REFERENCES users(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 21. BẢNG BÌNH LUẬN BÀI VIẾT (client bình luận)
-- =====================================================================
DROP TABLE IF EXISTS post_comments;
CREATE TABLE post_comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    post_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_id INT DEFAULT NULL COMMENT 'Trả lời bình luận khác (nếu có)',
    content TEXT NOT NULL,
    status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_comments_post FOREIGN KEY (post_id)
        REFERENCES posts(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comments_user FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_comments_parent FOREIGN KEY (parent_id)
        REFERENCES post_comments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- =====================================================================
-- 22. BẢNG BANNER / QUẢNG CÁO TRANG CHỦ (admin quản lý)
-- =====================================================================
DROP TABLE IF EXISTS banners;
CREATE TABLE banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    created_by INT DEFAULT NULL,
    title VARCHAR(150) DEFAULT NULL,
    image VARCHAR(255) NOT NULL,
    link_url VARCHAR(255) DEFAULT NULL,
    position ENUM('home_slider','home_side','category_top') NOT NULL DEFAULT 'home_slider',
    display_order INT NOT NULL DEFAULT 0,
    status TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_banners_creator FOREIGN KEY (created_by)
        REFERENCES users(id) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =====================================================================
-- DỮ LIỆU MẪU (tuỳ chọn) - có thể xoá phần này nếu không cần
-- =====================================================================
INSERT INTO users (full_name, email, password, role) VALUES
('Quản trị viên', 'admin@mobileshop.vn', '$2y$10$abcdefghijklmnopqrstuv', 'admin'),
('Nguyễn Văn A', 'client@mobileshop.vn', '$2y$10$abcdefghijklmnopqrstuv', 'client');

INSERT INTO brands (name, slug) VALUES
('Apple', 'apple'), ('Samsung', 'samsung'), ('Xiaomi', 'xiaomi'), ('Oppo', 'oppo');

INSERT INTO categories (parent_id, name, slug) VALUES
(NULL, 'Điện thoại', 'dien-thoai'),
(NULL, 'Phụ kiện', 'phu-kien'),
(1, 'Điện thoại iPhone', 'dien-thoai-iphone');

INSERT INTO post_categories (name, slug) VALUES
('Tin công nghệ', 'tin-cong-nghe'),
('Đánh giá sản phẩm', 'danh-gia-san-pham');
