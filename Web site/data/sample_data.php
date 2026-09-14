<?php
// Dữ liệu khởi tạo mặc định cho website IT Mobile

function get_initial_sample_data() {
    return [
        'categories' => [
            'phone' => [
                'id' => 'phone',
                'name' => 'Điện thoại',
                'icon' => 'fa-mobile-alt',
                'description' => 'Điện thoại thông minh chính hãng Apple, Samsung, Xiaomi, OPPO...'
            ],
            'tablet' => [
                'id' => 'tablet',
                'name' => 'Máy tính bảng',
                'icon' => 'fa-tablet-alt',
                'description' => 'iPad, Samsung Galaxy Tab, Xiaomi Pad cho học tập và làm việc'
            ],
            'accessory' => [
                'id' => 'accessory',
                'name' => 'Phụ kiện',
                'icon' => 'fa-headphones',
                'description' => 'Tai nghe Bluetooth, Sạc dự phòng, Ốp lưng, Cáp sạc cao cấp'
            ]
        ],

        'users' => [
            1 => [
                'id' => 1,
                'name' => 'Quản trị viên IT Mobile',
                'email' => 'admin@itmobile.vn',
                'password' => password_hash('admin123', PASSWORD_BCRYPT),
                'role' => 'admin',
                'phone' => '0901234567',
                'address' => 'Trụ sở IT Mobile, 100 Nguyễn Trãi, Thanh Xuân, Hà Nội',
                'created_at' => '2025-01-01 08:00:00'
            ],
            2 => [
                'id' => 2,
                'name' => 'Nguyễn Văn An',
                'email' => 'khachhang@gmail.com',
                'password' => password_hash('user123', PASSWORD_BCRYPT),
                'role' => 'customer',
                'phone' => '0987654321',
                'address' => 'Số 45 Lê Văn Lương, Cầu Giấy, Hà Nội',
                'created_at' => '2025-01-10 10:30:00'
            ]
        ],

        'products' => [
            1 => [
                'id' => 1,
                'name' => 'iPhone 16 Pro Max 256GB',
                'category_id' => 'phone',
                'brand' => 'Apple',
                'price' => 34990000,
                'sale_price' => 32990000,
                'image' => 'assets/images/iphone-16-pro-max.png',
                'description' => 'iPhone 16 Pro Max trang bị chip A18 Pro mạnh mẽ vượt trội, khung vỏ Titan cao cấp, nút Camera Control thế hệ mới cùng camera zoom quang học 5x sắc nét.',
                'specs' => [
                    'Màn hình' => '6.9 inch Super Retina XDR OLED 120Hz',
                    'Chip xử lý' => 'Apple A18 Pro 3nm',
                    'RAM' => '8 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => 'Chính 48MP, Góc siêu rộng 48MP, Tele 12MP (5x)',
                    'Camera trước' => '12 MP TrueDepth',
                    'Pin & Sạc' => '4685 mAh, Sạc nhanh 30W, MagSafe 25W'
                ],
                'colors' => ['Titan Tự Nhiên', 'Titan Sa Mạc', 'Titan Trắng', 'Titan Đen'],
                'storage' => ['256GB', '512GB', '1TB'],
                'stock' => 25,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => true
            ],
            2 => [
                'id' => 2,
                'name' => 'Samsung Galaxy S24 Ultra 5G',
                'category_id' => 'phone',
                'brand' => 'Samsung',
                'price' => 33990000,
                'sale_price' => 27990000,
                'image' => 'assets/images/galaxy-s24-ultra.png',
                'description' => 'Galaxy S24 Ultra đột phá công nghệ với Galaxy AI thông minh, khung viền Titan sang trọng, tích hợp bút S-Pen chuyên nghiệp và camera 200MP đỉnh cao siêu zoom 100x.',
                'specs' => [
                    'Màn hình' => '6.8 inch Dynamic AMOLED 2X 120Hz QHD+',
                    'Chip xử lý' => 'Snapdragon 8 Gen 3 for Galaxy',
                    'RAM' => '12 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => '200MP + 50MP + 12MP + 10MP',
                    'Camera trước' => '12 MP',
                    'Pin & Sạc' => '5000 mAh, Sạc siêu nhanh 45W'
                ],
                'colors' => ['Xám Titan', 'Đen Titan', 'Tím Titan', 'Vàng Titan'],
                'storage' => ['256GB', '512GB', '1TB'],
                'stock' => 18,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => false
            ],
            3 => [
                'id' => 3,
                'name' => 'Xiaomi 14 Ultra 5G',
                'category_id' => 'phone',
                'brand' => 'Xiaomi',
                'price' => 32990000,
                'sale_price' => 26990000,
                'image' => 'assets/images/xiaomi-14-ultra.png',
                'description' => 'Xiaomi 14 Ultra kết hợp cùng ống kính Leica 1 inch khẩu độ biến thiên, vi xử lý Snapdragon 8 Gen 3 đỉnh cao và màn hình AMOLED C8 siêu sáng.',
                'specs' => [
                    'Màn hình' => '6.73 inch LTPO AMOLED 120Hz 3000 nits',
                    'Chip xử lý' => 'Snapdragon 8 Gen 3',
                    'RAM' => '16 GB',
                    'Dung lượng' => '512 GB',
                    'Camera sau' => '4 camera Leica 50MP 1 inch',
                    'Camera trước' => '32 MP',
                    'Pin & Sạc' => '5000 mAh, Sạc dây 90W, Không dây 80W'
                ],
                'colors' => ['Đen Lưng Da', 'Trắng Lưng Da'],
                'storage' => ['512GB'],
                'stock' => 12,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => false,
                'is_new' => true
            ],
            4 => [
                'id' => 4,
                'name' => 'OPPO Find X7 Ultra',
                'category_id' => 'phone',
                'brand' => 'OPPO',
                'price' => 25990000,
                'sale_price' => 22990000,
                'image' => 'assets/images/oppo-find-x7-ultra.png',
                'description' => 'Flagship camera kép tiềm vọng đầu tiên trên thế giới đồng phát triển cùng Hasselblad, công nghệ xử lý hình ảnh HyperTone sắc nét.',
                'specs' => [
                    'Màn hình' => '6.82 inch LTPO AMOLED 2K 120Hz',
                    'Chip xử lý' => 'Snapdragon 8 Gen 3',
                    'RAM' => '12 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => '4 camera 50MP Hasselblad',
                    'Camera trước' => '32 MP',
                    'Pin & Sạc' => '5000 mAh, Sạc nhanh SuperVOOC 100W'
                ],
                'colors' => ['Xanh Hải Quân', 'Đen Nâu', 'Cát Vàng'],
                'storage' => ['256GB', '512GB'],
                'stock' => 10,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => false,
                'is_new' => true
            ],
            5 => [
                'id' => 5,
                'name' => 'iPad Pro 13 inch M4 Wi-Fi 256GB',
                'category_id' => 'tablet',
                'brand' => 'Apple',
                'price' => 37990000,
                'sale_price' => 35990000,
                'image' => 'assets/images/ipad-pro-m4.png',
                'description' => 'iPad Pro mỏng nhẹ kỷ lục trang bị chip Apple M4 thế hệ mới, màn hình Ultra Retina XDR OLED 2 lớp tân tiến nhất.',
                'specs' => [
                    'Màn hình' => '13.0 inch Ultra Retina XDR OLED ProMotion 120Hz',
                    'Chip xử lý' => 'Apple M4 9-core/10-core',
                    'RAM' => '8 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => '12 MP f/1.8 + Cảm biến LiDar',
                    'Camera trước' => '12 MP Ultra Wide ngang',
                    'Pin & Sạc' => '38.99 Wh, Sạc nhanh 30W'
                ],
                'colors' => ['Bạc (Silver)', 'Đen Không Gian (Space Black)'],
                'storage' => ['256GB', '512GB', '1TB'],
                'stock' => 15,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => true
            ],
            6 => [
                'id' => 6,
                'name' => 'Samsung Galaxy Tab S9 Ultra',
                'category_id' => 'tablet',
                'brand' => 'Samsung',
                'price' => 30990000,
                'sale_price' => 24990000,
                'image' => 'assets/images/galaxy-tab-s9-ultra.png',
                'description' => 'Máy tính bảng màn hình khổng lồ 14.6 inch Dynamic AMOLED 2X, chuẩn kháng nước IP68 đầu tiên trên dòng Tab S, kèm sẵn bút S-Pen.',
                'specs' => [
                    'Màn hình' => '14.6 inch Dynamic AMOLED 2X 120Hz',
                    'Chip xử lý' => 'Snapdragon 8 Gen 2 for Galaxy',
                    'RAM' => '12 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => '13MP + 8MP Ultra Wide',
                    'Camera trước' => 'Kép 12MP + 12MP',
                    'Pin & Sạc' => '11200 mAh, Sạc nhanh 45W'
                ],
                'colors' => ['Đen Khói', 'Kem Sa Mạc'],
                'storage' => ['256GB', '512GB'],
                'stock' => 8,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => false,
                'is_new' => false
            ],
            7 => [
                'id' => 7,
                'name' => 'Xiaomi Pad 6S Pro 12.4',
                'category_id' => 'tablet',
                'brand' => 'Xiaomi',
                'price' => 14990000,
                'sale_price' => 12990000,
                'image' => 'assets/images/xiaomi-pad-6s-pro.png',
                'description' => 'Tablet hiệu năng cao màn hình 3K 144Hz, sạc siêu tốc 120W HyperCharge cùng hệ điều hành Xiaomi HyperOS mượt mà.',
                'specs' => [
                    'Màn hình' => '12.4 inch IPS LCD 3K 144Hz 900 nits',
                    'Chip xử lý' => 'Snapdragon 8 Gen 2',
                    'RAM' => '8 GB',
                    'Dung lượng' => '256 GB',
                    'Camera sau' => '50 MP + 2 MP',
                    'Camera trước' => '32 MP',
                    'Pin & Sạc' => '10000 mAh, Sạc nhanh 120W'
                ],
                'colors' => ['Xám Thạch Anh'],
                'storage' => ['256GB', '512GB'],
                'stock' => 20,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => true,
                'is_new' => true
            ],
            8 => [
                'id' => 8,
                'name' => 'Tai nghe Apple AirPods Pro 2 USB-C',
                'category_id' => 'accessory',
                'brand' => 'Apple',
                'price' => 6190000,
                'sale_price' => 5490000,
                'image' => 'assets/images/airpods-pro-2.png',
                'description' => 'Tai nghe chống ồn chủ động ANC gấp 2 lần, hộp sạc chuẩn USB-C hỗ trợ MagSafe, âm thanh vòm Spatial Audio chủ động.',
                'specs' => [
                    'Chip audio' => 'Apple H2',
                    'Kết nối' => 'Bluetooth 5.3',
                    'Thời lượng pin' => '6 giờ liên tục (30 giờ kèm hộp sạc)',
                    'Kháng nước' => 'IP54',
                    'Cổng sạc' => 'USB-C & MagSafe không dây'
                ],
                'colors' => ['Trắng'],
                'storage' => ['Tiêu chuẩn'],
                'stock' => 40,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => false
            ],
            9 => [
                'id' => 9,
                'name' => 'Sạc dự phòng Anker Prime 20,000mAh 200W',
                'category_id' => 'accessory',
                'brand' => 'Anker',
                'price' => 3200000,
                'sale_price' => 2690000,
                'image' => 'assets/images/anker-prime-200w.png',
                'description' => 'Trạm sạc di động công suất tổng 200W, trang bị màn hình thông minh TFT hiển thị công suất thực thời gian thực.',
                'specs' => [
                    'Dung lượng' => '20,000 mAh',
                    'Cổng ra' => '2x USB-C (100W max từng cổng), 1x USB-A (65W max)',
                    'Công suất tổng' => '200W Max',
                    'Màn hình' => 'TFT LCD hiển thị dòng điện'
                ],
                'colors' => ['Đen Nhám'],
                'storage' => ['20000mAh'],
                'stock' => 30,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => true,
                'is_new' => true
            ],
            10 => [
                'id' => 10,
                'name' => 'Củ sạc nhanh Baseus GaN5 Pro 65W Trippel',
                'category_id' => 'accessory',
                'brand' => 'Baseus',
                'price' => 690000,
                'sale_price' => 490000,
                'image' => 'assets/images/baseus-gan5-65w.png',
                'description' => 'Củ sạc GaN thế hệ thứ 5 kích thước siêu nhỏ gọn, 3 cổng ra sạc đồng thời điện thoại, laptop và máy tính bảng.',
                'specs' => [
                    'Công suất' => '65W Max',
                    'Cổng giao tiếp' => '2x USB-C + 1x USB-A',
                    'Công nghệ' => 'GaN5 Pro, BPS II chia điện thông minh'
                ],
                'colors' => ['Trắng', 'Đen'],
                'storage' => ['65W'],
                'stock' => 50,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => false,
                'is_new' => false
            ],
            11 => [
                'id' => 11,
                'name' => 'Ốp lưng iPhone 16 Pro Max UAG Monarch Kevlar',
                'category_id' => 'accessory',
                'brand' => 'UAG',
                'price' => 1750000,
                'sale_price' => 1490000,
                'image' => 'assets/images/uag-monarch-kevlar.png',
                'description' => 'Ốp chống va đập chuẩn quân đội Mỹ 5 lớp bảo vệ tích hợp vật liệu sợi Kevlar siêu bền và nam châm MagSafe.',
                'specs' => [
                    'Chất liệu' => 'Kevlar Dupont + Khung Polycarbonate + Cao su chịu lực',
                    'Tiêu chuẩn' => 'Drop test MIL-STD 810G 516.6 (Rơi từ 6 mét)',
                    'Tương thích' => 'MagSafe & Sạc không dây Qi'
                ],
                'colors' => ['Đen Kevlar', 'Xanh Kevlar'],
                'storage' => ['Tiêu chuẩn'],
                'stock' => 25,
                'status' => 'active',
                'is_featured' => false,
                'is_bestseller' => false,
                'is_new' => true
            ],
            12 => [
                'id' => 12,
                'name' => 'Sony WH-1000XM5 Tai nghe Chống ồn cao cấp',
                'category_id' => 'accessory',
                'brand' => 'Sony',
                'price' => 8490000,
                'sale_price' => 7490000,
                'image' => 'assets/images/sony-wh-1000xm5.png',
                'description' => 'Dẫn đầu công nghệ chống ồn với bộ xử lý V1 & QN1 kép, 8 micro thu âm và màng loa 30mm cho chất âm Hi-Res sắc nét.',
                'specs' => [
                    'Chip xử lý' => 'Integrated Processor V1 + HD Noise Cancelling Processor QN1',
                    'Thời lượng pin' => '30 giờ (bật ANC), 40 giờ (tắt ANC)',
                    'Sạc nhanh' => '3 phút sạc cho 3 giờ nghe',
                    'Kết nối' => 'Bluetooth 5.2, LDAC, Multi-point'
                ],
                'colors' => ['Bạc Trắng', 'Đen Tuyền', 'Xanh Midnight'],
                'storage' => ['Tiêu chuẩn'],
                'stock' => 14,
                'status' => 'active',
                'is_featured' => true,
                'is_bestseller' => false,
                'is_new' => false
            ]
        ],

        'articles' => [
            1 => [
                'id' => 1,
                'title' => 'Đánh giá chi tiết iPhone 16 Pro Max: Nút Camera Control có thực sự hữu ích?',
                'slug' => 'danh-gia-chi-tiet-iphone-16-pro-max',
                'image' => 'assets/images/article-iphone16.png',
                'summary' => 'Khám phá trải nghiệm thực tế iPhone 16 Pro Max với nút phím cơ camera hoàn toàn mới, viền màn hình siêu mỏng và thời lượng pin ấn tượng.',
                'content' => 'iPhone 16 Pro Max là bước tiến đáng chú ý của Apple trong năm nay. Điểm nhấn lớn nhất đến từ phím bấm Camera Control cho phép vuốt chỉnh tiêu cự, khẩu độ và chụp ảnh cực kỳ nhanh chóng. Bên cạnh đó, chip A18 Pro hỗ trợ xử lý tác vụ AI cục bộ cực nhạy...',
                'author' => 'Ban Biên Tập IT Mobile',
                'created_at' => '2025-09-10 14:20:00',
                'status' => 'published'
            ],
            2 => [
                'id' => 2,
                'title' => 'Top 5 điện thoại tầm giá 15 - 20 triệu đáng mua nhất năm 2025',
                'slug' => 'top-5-dien-thoai-tam-gia-15-20-trieu',
                'image' => 'assets/images/article-top5.png',
                'summary' => 'Tổng hợp những mẫu smartphone cao cấp sở hữu cấu hình mạnh, camera đẹp và thiết kế sang trọng trong phân khúc tiệm cận flagship.',
                'content' => 'Phân khúc 15 đến 20 triệu đồng luôn là chiến trường khốc liệt giữa các hãng công nghệ lớn. Người dùng có thể lựa chọn từ các mẫu iPhone bản tiêu chuẩn đến các mẫu Android màn hình gập hoặc Android cấu hình khủng như Galaxy S24, Xiaomi 14...',
                'author' => 'Tuấn Anh - Tech Reviewer',
                'created_at' => '2025-08-25 09:15:00',
                'status' => 'published'
            ],
            3 => [
                'id' => 3,
                'title' => 'Hướng dẫn 8 mẹo tiết kiệm pin cực hiệu quả cho điện thoại Samsung Galaxy AI',
                'slug' => 'huong-dan-tiet-kiem-pin-samsung-galaxy-ai',
                'image' => 'assets/images/article-tips-battery.png',
                'summary' => 'Bí quyết giúp kéo dài thời gian sử dụng trên Galaxy S24 Series và các thiết bị One UI 6.1 bằng cách tối ưu hóa các tính năng trí tuệ nhân tạo.',
                'content' => 'Các tính năng AI mang lại sự tiện lợi vượt trội nhưng cũng tiêu tốn một lượng pin đáng kể nếu không cài đặt hợp lý. Bài viết này hướng dẫn bạn cách tối ưu màn hình Always On Display, chế độ quét tần số quét linh hoạt và giới hạn tiến trình nền...',
                'author' => 'IT Mobile Support Team',
                'created_at' => '2025-07-18 16:45:00',
                'status' => 'published'
            ],
            4 => [
                'id' => 4,
                'title' => 'Cách phân biệt củ sạc Anker & Baseus chính hãng tránh mua phải hàng giả',
                'slug' => 'phan-biet-cu-sac-chinh-hang-anker-baseus',
                'image' => 'assets/images/article-fake-charger.png',
                'summary' => 'Tránh nguy cơ cháy nổ thiết bị với các dấu hiệu nhận biết sạc cáp thật - giả chi tiết qua tem mã cào QR và độ hoàn thiện cơ khí.',
                'content' => 'Hiện nay thị trường phụ kiện công nghệ xuất hiện nhiều loại củ sạc nhái nhãn hiệu Anker, Baseus với linh kiện kém chất lượng. Hãy cùng IT Mobile kiểm tra tem chống hàng giả, trọng lượng thực tế và chân cắm để đảm bảo an toàn cho dế yêu của bạn...',
                'author' => 'Kỹ Thuật IT Mobile',
                'created_at' => '2025-06-05 11:00:00',
                'status' => 'published'
            ]
        ],

        'orders' => [
            'BM-20250315-1001' => [
                'id' => 'BM-20250315-1001',
                'customer_id' => 2,
                'customer_name' => 'Nguyễn Văn An',
                'customer_phone' => '0987654321',
                'customer_email' => 'khachhang@gmail.com',
                'customer_address' => 'Số 45 Lê Văn Lương, Cầu Giấy, Hà Nội',
                'note' => 'Giao hàng trong giờ hành chính',
                'items' => [
                    [
                        'product_id' => 1,
                        'product_name' => 'iPhone 16 Pro Max 256GB',
                        'color' => 'Titan Sa Mạc',
                        'storage' => '256GB',
                        'price' => 32990000,
                        'quantity' => 1,
                        'subtotal' => 32990000
                    ],
                    [
                        'product_id' => 8,
                        'product_name' => 'Tai nghe Apple AirPods Pro 2 USB-C',
                        'color' => 'Trắng',
                        'storage' => 'Tiêu chuẩn',
                        'price' => 5490000,
                        'quantity' => 1,
                        'subtotal' => 5490000
                    ]
                ],
                'total_amount' => 38480000,
                'payment_method' => 'online',
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'created_at' => '2025-03-15 14:30:00',
                'completed_at' => '2025-03-17 10:00:00'
            ],
            'BM-20250620-1002' => [
                'id' => 'BM-20250620-1002',
                'customer_id' => 2,
                'customer_name' => 'Nguyễn Văn An',
                'customer_phone' => '0987654321',
                'customer_email' => 'khachhang@gmail.com',
                'customer_address' => 'Số 45 Lê Văn Lương, Cầu Giấy, Hà Nội',
                'note' => 'Gọi trước khi giao 15 phút',
                'items' => [
                    [
                        'product_id' => 2,
                        'product_name' => 'Samsung Galaxy S24 Ultra 5G',
                        'color' => 'Xám Titan',
                        'storage' => '256GB',
                        'price' => 27990000,
                        'quantity' => 1,
                        'subtotal' => 27990000
                    ]
                ],
                'total_amount' => 27990000,
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'order_status' => 'completed',
                'created_at' => '2025-06-20 09:15:00',
                'completed_at' => '2025-06-22 16:20:00'
            ],
            'BM-20250910-1003' => [
                'id' => 'BM-20250910-1003',
                'customer_id' => 2,
                'customer_name' => 'Nguyễn Văn An',
                'customer_phone' => '0987654321',
                'customer_email' => 'khachhang@gmail.com',
                'customer_address' => 'Số 45 Lê Văn Lương, Cầu Giấy, Hà Nội',
                'note' => '',
                'items' => [
                    [
                        'product_id' => 9,
                        'product_name' => 'Sạc dự phòng Anker Prime 20,000mAh 200W',
                        'color' => 'Đen Nhám',
                        'storage' => '20000mAh',
                        'price' => 2690000,
                        'quantity' => 2,
                        'subtotal' => 5380000
                    ]
                ],
                'total_amount' => 5380000,
                'payment_method' => 'online',
                'payment_status' => 'unpaid',
                'order_status' => 'cancelled',
                'created_at' => '2025-09-10 11:00:00',
                'completed_at' => null
            ]
        ]
    ];
}
