# Bảng Đối Chiếu 10 Yêu Cầu Bài Tập Lớn — Website "IT Mobile"

Bảng dưới đây trình bày chi tiết mức độ đáp ứng, hiện trạng tính năng và hướng dẫn chuyển đổi khi nâng cấp sang cơ sở dữ liệu MySQL và môi trường thực tế.

---

## Bảng Tổng Hợp Trạng Thái 10 Yêu Cầu

| STT | Yêu cầu bài tập lớn | Trạng thái hiện tại | Mô tả chi tiết & Ghi chú kỹ thuật | Phần cần hoàn thiện khi nâng cấp |
| :--- | :--- | :--- | :--- | :--- |
| **Yêu cầu 1** | **Chạy trên Internet** | **Chờ triển khai và kiểm tra thực tế** | Code đã được viết chuẩn hóa với `config.php` tự động xác định `BASE_URL` động, không hardcode localhost. Đã có tài liệu `deployment_guide.md` chi tiết cho SV IS & IT. | Đưa lên Hosting/VPS thực tế và đăng ký domain. |
| **Yêu cầu 2** | **Trang chủ & Phân loại sản phẩm** | **Đã hoàn thành** | Đã có Banner quảng cáo, 3 danh mục (Điện thoại, Máy tính bảng, Phụ kiện), SP nổi bật, bán chạy, mới. Đã có tìm kiếm tên, lọc danh mục/thương hiệu/khoảng giá và sắp xếp giá. | Thay dữ liệu Session bằng bảng `products` & `categories` trong MySQL. |
| **Yêu cầu 3** | **Trang giới thiệu & Bài viết** | **Đã hoàn thành** | Đã có trang `about.php` giới thiệu cửa hàng và `news.php` chứa danh sách/chi tiết bài viết tư vấn, mẹo sử dụng và tin công nghệ (4+ bài viết mẫu). | Thay mảng bài viết bằng bảng `articles` trong MySQL. |
| **Yêu cầu 4** | **Trang chi tiết sản phẩm** | **Đã hoàn thành** | Hiển thị ảnh lớn, thông tin giá, mô tả, bảng thông số kỹ thuật, tùy chọn màu sắc & dung lượng, số lượng mua, nút "Thêm vào giỏ", "Mua ngay", SP cùng danh mục. Xử lý ID không hợp lệ thông báo lỗi & nút quay lại. | Truy vấn chi tiết sản phẩm theo ID từ CSDL. |
| **Yêu cầu 5** | **Giỏ hàng & Đặt hàng online** | **Đã hoàn thành** | Giỏ hàng hỗ trợ thêm, cập nhật số lượng, xóa và tính tổng tiền phía PHP. Form đặt hàng validate thông tin phía PHP. Lưu đơn hàng dạng Snapshot vào Session, tạo mã đơn, hiển thị xác nhận và xem danh sách đơn cá nhân. | Lưu đơn hàng vào bảng `orders` và `order_items`. |
| **Yêu cầu 6** | **Thanh toán trực tuyến** | **Đang mô phỏng, chờ tích hợp cổng thanh toán sandbox** | Đã thiết kế lựa chọn COD và Thanh toán trực tuyến mô phỏng (`payment-process.php`) với 3 kịch bản: Thành công, Thất bại, Hủy. Gắn nhãn mô phỏng rõ ràng, không thu thập thẻ thật. Tách rời logic xử lý. Cho phép thử lại trên cùng mã đơn. | Tích hợp SDK cổng thanh toán thực tế (VNPay / MoMo / ZaloPay Sandbox). |
| **Yêu cầu 7** | **Đăng nhập & Phân quyền** | **Đã hoàn thành** | Phân quyền 2 vai trò: Customer & Admin cấp PHP (`require_login()`, `require_admin()`, kiểm tra sở hữu đơn hàng). Đăng ký mã hóa `password_hash()`, đăng nhập kiểm tra `password_verify()`. Tự động regenerates Session ID. Nút đăng nhập Demo nhanh bật khi `DEMO_MODE=true`. | Thay mảng người dùng bằng bảng `users` trong CSDL. |
| **Yêu cầu 8** | **Trang đăng sản phẩm & Bài viết (Admin)** | **Đã hoàn thành** | Khu vực Admin có Sidebar chuyên nghiệp. CRUD Sản phẩm và Bài viết. Chọn ảnh từ bộ ảnh mẫu dự án. Thay đổi cập nhật tức thì trong phiên. Có nút xác nhận xóa JS và thông báo Flash alert. | Thực hiện câu lệnh INSERT/UPDATE/DELETE SQL tới CSDL. |
| **Yêu cầu 9** | **Trang xử lý đơn hàng (Admin)** | **Đã hoàn thành** | Admin xem danh sách đơn, tìm theo mã/tên/sđt, lọc trạng thái. Cho phép cập nhật chuyển trạng thái chuẩn (`Chờ xác nhận` → `Đã xác nhận` → `Đang giao` → `Hoàn thành` / `Hủy`). Tách riêng trạng thái đơn và thanh toán. Dùng POST + CSRF token. | Cập nhật trường status trong bảng `orders`. |
| **Yêu cầu 10** | **Thống kê đơn hàng (Admin)** | **Đã hoàn thành** | Thống kê theo Tháng, Quý, Năm với bộ lọc thời gian. Tính toán số đơn, số thiết bị bán và tổng doanh thu **từ các đơn Hoàn thành** (Loại bỏ đơn Hủy). Có biểu đồ cột CSS và bảng Top sản phẩm bán chạy đồng bộ bộ lọc. | Thực hiện các câu lệnh SQL `SUM()`, `COUNT()`, `GROUP BY` trên CSDL. |

---

## Ghi Chú Phụ Thuộc Môi Trường Mô Phỏng Session

> [!NOTE]
> **Đặc điểm của PHP Session:**
> - Toàn bộ dữ liệu sản phẩm, bài viết, người dùng và đơn hàng tạo mới/chỉnh sửa chỉ tồn tại tạm thời trong Session của trình duyệt hiện tại.
> - Khi đăng xuất tài khoản (`logout.php`), chỉ xóa thông tin xác thực `$_SESSION['user']`, **KHÔNG** làm mất dữ liệu đơn hàng hay sản phẩm đã sửa.
> - Quản trị viên có thể bấm nút **"Khôi phục dữ liệu mẫu"** trong trang Admin để đặt lại toàn bộ dữ liệu về trạng thái ban đầu khi cần.
