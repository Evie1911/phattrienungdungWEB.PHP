<?php
// Helper render ảnh với Fallback SVG mượt mà khi chưa có tệp ảnh tĩnh PNG/JPG

function get_image_src($image_path, $alt_text = 'IT Mobile', $width = 400, $height = 400, $bg_color = '0066cc') {
    $full_path = ROOT_PATH . '/' . ltrim($image_path, '/');
    if (!empty($image_path) && file_exists($full_path)) {
        return BASE_URL . '/' . ltrim($image_path, '/');
    }

    // Tự động tạo chuỗi SVG Data URI sắc nét với tên IT Mobile
    $title = htmlspecialchars($alt_text, ENT_QUOTES, 'UTF-8');
    
    $gradient_start = "#0052cc";
    $gradient_end = "#0088ff";
    if (strpos(strtolower($alt_text), 'iphone') !== false || strpos(strtolower($alt_text), 'apple') !== false) {
        $gradient_start = "#1c1c1e";
        $gradient_end = "#3a3a3c";
    } elseif (strpos(strtolower($alt_text), 'galaxy') !== false || strpos(strtolower($alt_text), 'samsung') !== false) {
        $gradient_start = "#031b4e";
        $gradient_end = "#0d47a1";
    } elseif (strpos(strtolower($alt_text), 'xiaomi') !== false) {
        $gradient_start = "#d84315";
        $gradient_end = "#ff6d00";
    } elseif (strpos(strtolower($alt_text), 'tin tức') !== false || strpos(strtolower($alt_text), 'đánh giá') !== false) {
        $gradient_start = "#0f2027";
        $gradient_end = "#203a43";
    }

    $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$width}" height="{$height}" viewBox="0 0 {$width} {$height}">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$gradient_start};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$gradient_end};stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="100%" height="100%" fill="url(#grad)" />
  <circle cx="50%" cy="40%" r="45" fill="rgba(255,255,255,0.15)" />
  <path d="M {$width}*0.4 120 L {$width}*0.6 120" stroke="#ffffff" stroke-width="4" stroke-linecap="round" />
  <text x="50%" y="42%" font-family="system-ui, -apple-system, sans-serif" font-size="26" font-weight="bold" fill="#ffffff" text-anchor="middle" dominant-baseline="middle">IT Mobile</text>
  <text x="50%" y="65%" font-family="system-ui, -apple-system, sans-serif" font-size="13" font-weight="600" fill="#e0e0e0" text-anchor="middle" dominant-baseline="middle">{$title}</text>
</svg>
SVG;

    return 'data:image/svg+xml;base64,' . base64_encode($svg);
}
