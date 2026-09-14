# Hướng Dẫn Triển Khai Website "IT Mobile" Lên Môi Trường Internet

Tài liệu này cung cấp hướng dẫn từng bước chi tiết giúp triển khai mã nguồn PHP thuần của website IT Mobile lên Internet theo 2 trường hợp cụ thể.

---

## 1. Nguyên Tắc Cấu Hình Đường Dẫn Động

Mã nguồn IT Mobile đã được tối ưu hóa trong tệp `config.php`:
- Không hardcode bất kỳ đường dẫn `http://localhost/...` nào.
- Hệ thống tự động xác định `BASE_URL` và `ROOT_PATH` dựa trên cổng HTTP/HTTPS và tên miền/thư mục chạy thực tế.
- Nhờ đó, bạn chỉ cần nén toàn bộ mã nguồn và tải lên máy chủ mà không cần phải chỉnh sửa lại mã nguồn PHP.

---

## 2. Trường Hợp 1: Sinh Viên Hệ Thống Thông Tin (IS) — Triển Khai Lên Web Hosting (Free / Paid)

Đối với sinh viên IS, lựa chọn phổ biến là sử dụng các dịch vụ Hosting có bảng điều khiển **cPanel** hoặc **DirectAdmin** (Ví dụ: InfinityFree, Hostinger, AZDIGI, v.v.).

### Bước 1: Nén mã nguồn
1. Mở thư mục dự án `c:\xampp\htdocs\Web site`.
2. Nén toàn bộ các tệp và thư mục (`index.php`, `config.php`, `data/`, `includes/`, `admin/`, `assets/`...) thành 1 file ZIP (`itmobile.zip`).

### Bước 2: Tải lên Web Hosting
1. Đăng nhập vào bảng điều khiển **cPanel** hoặc **DirectAdmin** của nhà cung cấp Hosting.
2. Mở công cụ **File Manager (Quản lý tệp)**.
3. Truy cập vào thư mục gốc hiển thị website (Thường là `public_html` hoặc `htdocs`).
4. Tải tệp `itmobile.zip` lên và chọn **Extract (Giải nén)** ngay tại thư mục đó.

### Bước 3: Kiểm tra & Phân quyền
1. Đảm bảo các thư mục có quyền ghi/đọc phù hợp (Quyền mặc định 755 cho thư mục và 644 cho file).
2. Truy cập tên miền đã đăng ký (hoặc sub-domain do hosting cấp, ví dụ: `http://itmobile.infinityfreeapp.com`).
3. Thử nghiệm toàn bộ các luồng thao tác: Đăng nhập, Thêm giỏ hàng, Đặt hàng, Trang quản trị Admin.

---

## 3. Trường Hợp 2: Sinh Viên Công Nghệ Thông Tin (IT) — Tự Dựng Máy Chủ VPS / Server (Ubuntu Linux)

Đối với sinh viên IT muốn tự dựng và quản trị máy chủ riêng trên VPS (AWS, DigitalOcean, Linode, Google Cloud hoặc máy tính cá nhân cài Linux/Windows Server).

### Bước 1: Cài đặt Web Server (Apache/Nginx) & PHP
Trên máy chủ **Ubuntu 22.04 / 24.04 LTS**:
```bash
# Cập nhật hệ thống
sudo apt update && sudo apt upgrade -y

# Cài đặt Apache và PHP cùng các module cần thiết
sudo apt install apache2 php libapache2-mod-php php-cli php-json php-mbstring -y

# Kích hoạt module rewrite nếu cần
sudo a2enmod rewrite
sudo systemctl restart apache2
```

### Bước 2: Tải mã nguồn lên máy chủ
```bash
# Truy cập thư mục web mặc định của Apache
cd /var/www/html

# Xóa trang mặc định index.html của Apache
sudo rm index.html

# Sao chép hoặc Git clone mã nguồn IT Mobile vào thư mục /var/www/html
# Cấp quyền cho Apache (www-data) có quyền đọc ghi
sudo chown -R www-data:www-data /var/www/html
sudo chmod -R 755 /var/www/html
```

### Bước 3: Cấu hình VirtualHost & Tên miền (Domain)
Tạo file cấu hình VirtualHost cho Apache:
```bash
sudo nano /etc/apache2/sites-available/itmobile.conf
```

Nội dung cấu hình:
```apache
<VirtualHost *:80>
    ServerName itmobile.example.com
    DocumentRoot /var/www/html

    <Directory /var/www/html>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/itmobile_error.log
    CustomLog ${APACHE_LOG_DIR}/itmobile_access.log combined
</VirtualHost>
```

Kích hoạt trang web và khởi động lại Apache:
```bash
sudo a2ensite itmobile.conf
sudo systemctl reload apache2
```

### Bước 4: Cấu hình Chứng chỉ SSL Miễn phí (Let's Encrypt / HTTPS)
```bash
# Cài đặt Certbot cho Apache
sudo apt install certbot python3-certbot-apache -y

# Khởi tạo chứng chỉ SSL tự động
sudo certbot --apache -d itmobile.example.com
```

---

## 4. Hướng Dẫn Nâng Cấp Sang MySQL & Cổng Thanh Toán Thật (Khi Học Các Môn Nâng Cao)

Khi bạn muốn nâng cấp dự án này từ bài tập lớn sang sản phẩm thực tế:
1. **Kết nối MySQL:** Thay thế các hàm trong `data/data_helper.php` bằng các truy vấn SQL PDO. Cấu trúc mảng trong `sample_data.php` chính là thiết kế bảng dữ liệu tương ứng (`users`, `products`, `categories`, `articles`, `orders`, `order_items`).
2. **Tích hợp Cổng thanh toán:** Thay thế luồng trong `payment-process.php` bằng SDK của VNPay, MoMo hoặc ZaloPay Sandbox.
