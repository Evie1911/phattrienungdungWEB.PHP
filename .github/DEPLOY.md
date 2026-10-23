# Deploy qua GitHub Actions

Workflow `workflows/deploy.yml` chạy kiểm tra cú pháp PHP cho các pull request và thay đổi trên `main`. Khi một commit được push lên `main`, workflow chỉ đồng bộ thư mục `Web_site/` lên máy chủ sau khi lint thành công.

## GitHub Secrets cần tạo

Trong repository, mở **Settings → Environments → production → Environment secrets** và thêm:

| Secret | Giá trị |
| --- | --- |
| `DEPLOY_HOST` | Hostname hoặc IP của máy chủ SSH |
| `DEPLOY_USER` | Tài khoản SSH có quyền ghi vào webroot |
| `DEPLOY_PATH` | Đường dẫn tuyệt đối tới webroot đã tồn tại, ví dụ `/home/account/public_html` |
| `DEPLOY_SSH_KEY` | Private key dùng để SSH; public key tương ứng phải nằm trong `authorized_keys` trên máy chủ |
| `DEPLOY_KNOWN_HOSTS` | Dòng host key SSH đã xác minh của máy chủ |
| `DEPLOY_PORT` | Không bắt buộc; mặc định là `22` |

Lấy host key bằng `ssh-keyscan -p <port> <host>` rồi đối chiếu fingerprint với nhà cung cấp máy chủ trước khi lưu vào `DEPLOY_KNOWN_HOSTS`. Workflow không tự tin cậy host key vừa quét trong lúc deploy.

`DEPLOY_PATH` phải trỏ tới thư mục document root và tồn tại trước. Workflow không dùng tùy chọn xóa file cũ, nhằm giữ ảnh upload/runtime trên máy chủ. Những file đã bị xóa khỏi source có thể cần được dọn thủ công trên máy chủ.

## Cấu hình `.env` trên máy chủ

Workflow không tải `.env` lên máy chủ. Tạo `.env` ở thư mục cha của webroot, nơi `Web_site/config.php` có thể đọc được, với các biến tương ứng trong [.env.example](../.env.example). Điền thông tin database production vào `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE`; điền `VNPAY_TMN_CODE`, `VNPAY_HASH_SECRET`, `VNPAY_RETURN_URL` và `VNPAY_IPN_URL` theo tài khoản VNPay và domain production.

Trong trang cấu hình merchant VNPay, khai báo IPN URL trùng với `VNPAY_IPN_URL`. Cả Return URL và IPN URL phải là URL HTTPS công khai trỏ tới các file tương ứng trong webroot.

Sau khi cấu hình secrets và server, push thay đổi vào `main`; job deploy sẽ tự chạy sau khi lint hoàn tất. Có thể chạy thủ công bằng **Actions → PHP CI and Deploy → Run workflow** trên nhánh `main`.
