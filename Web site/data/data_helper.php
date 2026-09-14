<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/sample_data.php';

// Khởi tạo dữ liệu mẫu trong PHP Session
function init_session_data() {
    if (!isset($_SESSION['data']) || empty($_SESSION['data'])) {
        $_SESSION['data'] = get_initial_sample_data();
    }
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
}
init_session_data();

// Lấy danh sách sản phẩm (có lọc và tìm kiếm)
function get_all_products($filters = []) {
    $products = $_SESSION['data']['products'] ?? [];
    $result = [];

    foreach ($products as $p) {
        // Lọc theo từ khóa tìm kiếm
        if (!empty($filters['keyword'])) {
            $kw = mb_strtolower(trim($filters['keyword']), 'UTF-8');
            $name = mb_strtolower($p['name'], 'UTF-8');
            $brand = mb_strtolower($p['brand'], 'UTF-8');
            if (strpos($name, $kw) === false && strpos($brand, $kw) === false) {
                continue;
            }
        }

        // Lọc theo danh mục
        if (!empty($filters['category'])) {
            if ($p['category_id'] !== $filters['category']) {
                continue;
            }
        }

        // Lọc theo thương hiệu
        if (!empty($filters['brand'])) {
            if (strtolower($p['brand']) !== strtolower($filters['brand'])) {
                continue;
            }
        }

        // Lọc theo khoảng giá
        $effective_price = !empty($p['sale_price']) ? $p['sale_price'] : $p['price'];
        if (!empty($filters['min_price']) && $effective_price < (float)$filters['min_price']) {
            continue;
        }
        if (!empty($filters['max_price']) && $effective_price > (float)$filters['max_price']) {
            continue;
        }

        $result[] = $p;
    }

    // Sắp xếp sản phẩm
    if (!empty($filters['sort'])) {
        if ($filters['sort'] === 'price_asc') {
            usort($result, function($a, $b) {
                $pa = !empty($a['sale_price']) ? $a['sale_price'] : $a['price'];
                $pb = !empty($b['sale_price']) ? $b['sale_price'] : $b['price'];
                return ($pa < $pb) ? -1 : 1;
            });
        } elseif ($filters['sort'] === 'price_desc') {
            usort($result, function($a, $b) {
                $pa = !empty($a['sale_price']) ? $a['sale_price'] : $a['price'];
                $pb = !empty($b['sale_price']) ? $b['sale_price'] : $b['price'];
                return ($pa > $pb) ? -1 : 1;
            });
        } elseif ($filters['sort'] === 'newest') {
            usort($result, function($a, $b) {
                return ($b['id'] < $a['id']) ? -1 : 1;
            });
        }
    }

    return $result;
}

// Lấy sản phẩm theo ID
function get_product_by_id($id) {
    $id = (int)$id;
    return $_SESSION['data']['products'][$id] ?? null;
}

// Lấy tất cả danh mục
function get_all_categories() {
    return $_SESSION['data']['categories'] ?? [];
}

// Lấy danh sách bài viết
function get_all_articles($search = '') {
    $articles = $_SESSION['data']['articles'] ?? [];
    if (!empty($search)) {
        $kw = mb_strtolower(trim($search), 'UTF-8');
        $filtered = [];
        foreach ($articles as $a) {
            $title = mb_strtolower($a['title'], 'UTF-8');
            $summary = mb_strtolower($a['summary'], 'UTF-8');
            if (strpos($title, $kw) !== false || strpos($summary, $kw) !== false) {
                $filtered[] = $a;
            }
        }
        return $filtered;
    }
    return $articles;
}

// Lấy bài viết theo ID
function get_article_by_id($id) {
    $id = (int)$id;
    return $_SESSION['data']['articles'][$id] ?? null;
}

// ----------------------------------------------------
// XỬ LÝ GIỎ HÀNG (SESSION)
// ----------------------------------------------------

function get_cart_items() {
    return $_SESSION['cart'] ?? [];
}

function add_to_cart($product_id, $quantity = 1, $color = '', $storage = '') {
    $product = get_product_by_id($product_id);
    if (!$product) {
        return false;
    }

    $quantity = max(1, (int)$quantity);
    if ($quantity > $product['stock']) {
        $quantity = $product['stock'];
    }

    if (empty($color) && !empty($product['colors'])) {
        $color = $product['colors'][0];
    }
    if (empty($storage) && !empty($product['storage'])) {
        $storage = $product['storage'][0];
    }

    $item_key = $product_id . '_' . md5($color . '_' . $storage);

    if (isset($_SESSION['cart'][$item_key])) {
        $new_qty = $_SESSION['cart'][$item_key]['quantity'] + $quantity;
        if ($new_qty > $product['stock']) {
            $new_qty = $product['stock'];
        }
        $_SESSION['cart'][$item_key]['quantity'] = $new_qty;
    } else {
        $unit_price = !empty($product['sale_price']) ? $product['sale_price'] : $product['price'];
        $_SESSION['cart'][$item_key] = [
            'key' => $item_key,
            'product_id' => $product['id'],
            'name' => $product['name'],
            'image' => $product['image'],
            'color' => $color,
            'storage' => $storage,
            'price' => $unit_price,
            'quantity' => $quantity
        ];
    }
    return true;
}

function update_cart_quantity($item_key, $quantity) {
    if (isset($_SESSION['cart'][$item_key])) {
        $quantity = (int)$quantity;
        if ($quantity <= 0) {
            unset($_SESSION['cart'][$item_key]);
        } else {
            $product_id = $_SESSION['cart'][$item_key]['product_id'];
            $product = get_product_by_id($product_id);
            if ($product && $quantity > $product['stock']) {
                $quantity = $product['stock'];
            }
            $_SESSION['cart'][$item_key]['quantity'] = $quantity;
        }
    }
}

function remove_from_cart($item_key) {
    if (isset($_SESSION['cart'][$item_key])) {
        unset($_SESSION['cart'][$item_key]);
    }
}

function clear_cart() {
    $_SESSION['cart'] = [];
}

function calculate_cart_totals() {
    $cart = get_cart_items();
    $total_amount = 0;
    $total_count = 0;

    foreach ($cart as $item) {
        $product = get_product_by_id($item['product_id']);
        $unit_price = ($product && !empty($product['sale_price'])) ? $product['sale_price'] : ($product ? $product['price'] : $item['price']);
        
        $subtotal = $unit_price * $item['quantity'];
        $total_amount += $subtotal;
        $total_count += $item['quantity'];
    }

    return [
        'total_amount' => $total_amount,
        'total_count' => $total_count
    ];
}

// ----------------------------------------------------
// XỬ LÝ ĐẶT HÀNG
// ----------------------------------------------------

function create_order_from_cart($shipping_info, $payment_method) {
    $cart = get_cart_items();
    if (empty($cart)) {
        return false;
    }

    $order_id = 'IT-' . date('Ymd') . '-' . rand(1000, 9999);
    $order_items = [];
    $total_amount = 0;

    foreach ($cart as $item) {
        $product = get_product_by_id($item['product_id']);
        if (!$product) continue;

        $unit_price = !empty($product['sale_price']) ? $product['sale_price'] : $product['price'];
        $subtotal = $unit_price * $item['quantity'];
        $total_amount += $subtotal;

        $order_items[] = [
            'product_id' => $product['id'],
            'product_name' => $product['name'],
            'color' => $item['color'],
            'storage' => $item['storage'],
            'price' => $unit_price,
            'quantity' => $item['quantity'],
            'subtotal' => $subtotal
        ];

        // Trừ kho
        if (isset($_SESSION['data']['products'][$product['id']])) {
            $_SESSION['data']['products'][$product['id']]['stock'] = max(0, $_SESSION['data']['products'][$product['id']]['stock'] - $item['quantity']);
        }
    }

    $order = [
        'id' => $order_id,
        'customer_name' => $shipping_info['name'],
        'customer_phone' => $shipping_info['phone'],
        'customer_email' => $shipping_info['email'],
        'customer_address' => $shipping_info['address'],
        'note' => $shipping_info['note'] ?? '',
        'items' => $order_items,
        'total_amount' => $total_amount,
        'payment_method' => $payment_method,
        'payment_status' => ($payment_method === 'online') ? 'paid' : 'unpaid',
        'order_status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];

    $_SESSION['data']['orders'][$order_id] = $order;
    clear_cart();

    return $order;
}

function get_order_by_id($id) {
    return $_SESSION['data']['orders'][$id] ?? null;
}
