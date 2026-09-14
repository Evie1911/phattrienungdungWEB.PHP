<?php
$page_title = 'Danh Sách Sản Phẩm';
require_once __DIR__ . '/includes/header.php';

// Lấy bộ lọc từ URL
$filters = [
    'keyword' => $_GET['keyword'] ?? '',
    'category' => $_GET['category'] ?? '',
    'brand' => $_GET['brand'] ?? '',
    'min_price' => $_GET['min_price'] ?? '',
    'max_price' => $_GET['max_price'] ?? '',
    'sort' => $_GET['sort'] ?? ''
];

$products = get_all_products($filters);
$categories = get_all_categories();

// Danh sách thương hiệu để lọc
$brands = ['Apple', 'Samsung', 'Xiaomi', 'OPPO', 'Sony', 'Anker', 'Baseus', 'UAG'];
?>

<div class="container">
    <div style="display: flex; gap: 24px; margin-bottom: 40px;">
        <!-- Sidebar Filter -->
        <aside style="width: 280px; flex-shrink: 0;">
            <div style="background-color: #fff; padding: 20px; border-radius: var(--radius-lg); border: 1px solid var(--border-color); box-shadow: var(--shadow-sm);">
                <h3 style="font-size: 1.1rem; font-weight: 700; margin-bottom: 16px; border-bottom: 2px solid var(--primary-light); padding-bottom: 10px;">
                    <i class="fas fa-filter text-primary"></i> Bộ Lọc Tìm Kiếm
                </h3>

                <form action="<?php echo BASE_URL; ?>/products.php" method="GET">
                    <?php if (!empty($filters['keyword'])): ?>
                        <input type="hidden" name="keyword" value="<?php echo sanitize($filters['keyword']); ?>">
                    <?php endif; ?>

                    <!-- Danh mục -->
                    <div class="form-group">
                        <label class="form-label">Danh mục sản phẩm</label>
                        <select name="category" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Tất cả danh mục --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo $cat['id']; ?>" <?php echo $filters['category'] === $cat['id'] ? 'selected' : ''; ?>>
                                    <?php echo sanitize($cat['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Thương hiệu -->
                    <div class="form-group">
                        <label class="form-label">Thương hiệu</label>
                        <select name="brand" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Tất cả thương hiệu --</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?php echo strtolower($b); ?>" <?php echo strtolower($filters['brand']) === strtolower($b) ? 'selected' : ''; ?>>
                                    <?php echo $b; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Khoảng giá -->
                    <div class="form-group">
                        <label class="form-label">Khoảng giá (VNĐ)</label>
                        <div style="display: flex; gap: 8px; align-items: center;">
                            <input type="number" name="min_price" class="form-control" placeholder="Từ" value="<?php echo sanitize($filters['min_price']); ?>" style="padding: 6px 10px; font-size: 0.85rem;">
                            <span>-</span>
                            <input type="number" name="max_price" class="form-control" placeholder="Đến" value="<?php echo sanitize($filters['max_price']); ?>" style="padding: 6px 10px; font-size: 0.85rem;">
                        </div>
                    </div>

                    <!-- Sắp xếp -->
                    <div class="form-group">
                        <label class="form-label">Sắp xếp theo</label>
                        <select name="sort" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Nổi bật --</option>
                            <option value="price_asc" <?php echo $filters['sort'] === 'price_asc' ? 'selected' : ''; ?>>Giá từ thấp đến cao</option>
                            <option value="price_desc" <?php echo $filters['sort'] === 'price_desc' ? 'selected' : ''; ?>>Giá từ cao đến thấp</option>
                            <option value="newest" <?php echo $filters['sort'] === 'newest' ? 'selected' : ''; ?>>Sản phẩm mới nhất</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-search"></i> Áp dụng bộ lọc
                    </button>
                    <?php if (array_filter($filters)): ?>
                        <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-secondary btn-block" style="margin-top: 10px; font-size: 0.85rem;">
                            <i class="fas fa-undo"></i> Xóa bộ lọc
                        </a>
                    <?php endif; ?>
                </form>
            </div>
        </aside>

        <!-- Product Grid Main Area -->
        <main style="flex: 1;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; background: #fff; padding: 16px 20px; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                <h1 style="font-size: 1.3rem; font-weight: 700;">
                    <?php 
                        if (!empty($filters['keyword'])) {
                            echo 'Kết quả tìm kiếm cho: "' . sanitize($filters['keyword']) . '"';
                        } elseif (!empty($filters['category']) && isset($categories[$filters['category']])) {
                            echo sanitize($categories[$filters['category']]['name']);
                        } else {
                            echo 'Tất Cả Sản Phẩm';
                        }
                    ?>
                </h1>
                <span style="font-size: 0.9rem; color: var(--text-secondary);">Tìm thấy <strong><?php echo count($products); ?></strong> sản phẩm</span>
            </div>

            <?php if (!empty($products)): ?>
                <div class="product-grid">
                    <?php foreach ($products as $product): ?>
                        <?php include __DIR__ . '/includes/product_card.php'; ?>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="background: #fff; padding: 60px 20px; text-align: center; border-radius: var(--radius-lg); border: 1px solid var(--border-color);">
                    <i class="fas fa-box-open" style="font-size: 3.5rem; color: var(--text-muted); margin-bottom: 16px;"></i>
                    <h3 style="font-size: 1.3rem; margin-bottom: 8px;">Không tìm thấy sản phẩm phù hợp</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 20px;">Vui lòng thử lại với từ khóa khác hoặc xóa bớt các tiêu chí lọc.</p>
                    <a href="<?php echo BASE_URL; ?>/products.php" class="btn btn-primary">
                        <i class="fas fa-redo"></i> Xem tất cả sản phẩm
                    </a>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
