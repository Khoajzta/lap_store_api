<?php
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

// Xóa domain và "/api" nếu có
$uri = parse_url($uri, PHP_URL_PATH);
$uri = str_replace('/api/', '', $uri); // ví dụ: SanPham/read.php

// Xây dựng đường dẫn file thật
$path = __DIR__ . '/' . $uri;

if (file_exists($path)) {
    require_once $path;
} else {
    http_response_code(404);
    echo json_encode(["message" => "Endpoint không tồn tại"]);
}
