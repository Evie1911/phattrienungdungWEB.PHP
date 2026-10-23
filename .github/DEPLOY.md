# Deploy qua GitHub Actions

Workflow `workflows/deploy.yml` kiểm tra cú pháp PHP trên pull request và tự upload thư mục `Web_site/` lên InfinityFree qua FTPS khi có thay đổi trên `main`.

## GitHub Environment secrets

Trong repository, mở **Settings → Environments → production → Environment secrets** và thêm:

| Secret | Giá trị |
| --- | --- |
| `FTP_USERNAME` | FTP Username trong Control Panel của InfinityFree |
| `FTP_PASSWORD` | FTP Password trong Control Panel (không phải mật khẩu database) |
| `FTP_SERVER_DIR` | Thư mục website trên FTP; thường là `/htdocs/` cho domain chính. Kiểm tra đường dẫn thực tế trong File Manager/FTP nếu domain dùng thư mục riêng. |

Workflow dùng máy chủ `ftpupload.net`, FTPS Explicit trên cổng `21`. Gói miễn phí không hỗ trợ SSH/SFTP. Không cần khai báo `DEPLOY_HOST`, `DEPLOY_USER`, `DEPLOY_PATH`, `DEPLOY_SSH_KEY` hay `DEPLOY_KNOWN_HOSTS`.

Các secret phải nằm trong Environment tên `production`, vì job deploy khai báo `environment: production`. FTP credentials có thể lấy từ phần FTP Accounts/FTP Details trong Control Panel. Sau khi lưu secrets, chạy **Actions → PHP CI and Deploy → Run workflow** trên nhánh `main` để thử deploy.

## File cấu hình ứng dụng

Workflow không tải thông tin bí mật lên hosting. InfinityFree giới hạn PHP trong `htdocs`, vì vậy không đặt `.env` ở thư mục cha. Tạo thủ công `htdocs/config/hosting-secrets.php` với nội dung PHP trả về mảng cấu hình bên dưới; file này bị loại khỏi Git và workflow deploy:

```php
<?php
return [
    'DB_HOST' => 'MySQL Hostname',
    'DB_PORT' => '3306',
    'DB_USERNAME' => 'MySQL Username',
    'DB_PASSWORD' => 'MySQL Password',
    'DB_DATABASE' => 'MySQL Database Name',
    'APP_URL' => 'https://cuahangnho.freedev.app',
    'VNPAY_TMN_CODE' => '',
    'VNPAY_HASH_SECRET' => '',
    'VNPAY_RETURN_URL' => 'https://cuahangnho.freedev.app/vnpay_return.php',
    'VNPAY_IPN_URL' => '',
];
```

Không commit file này lên Git. Lấy các giá trị MySQL trong **Control Panel → MySQL Databases**.

Điền `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`, `VNPAY_TMN_CODE`, `VNPAY_HASH_SECRET`, `VNPAY_RETURN_URL` và `VNPAY_IPN_URL` theo thông tin production. Return URL và IPN URL cần là HTTPS công khai; khai báo IPN URL tương ứng trong cấu hình merchant VNPay. Nếu đã từng đẩy thông tin database lên repository, hãy đổi mật khẩu database/account trên InfinityFree rồi cập nhật `.env` trên hosting và máy local.

Workflow không xóa các file cũ trên hosting. Nếu xóa file khỏi source, hãy kiểm tra và dọn file cũ trên hosting thủ công.
