<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// Lấy URI và phương thức HTTP
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Tách phần đường dẫn sau /api/
$basePath = '/api/';
$path = parse_url($requestUri, PHP_URL_PATH);

if (strpos($path, $basePath) !== 0) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid API path']);
    exit;
}

// Lấy phần sau /api/
$relativePath = substr($path, strlen($basePath));

// Tách thành phần folder và action.php
// VD: SanPham/read.php
$parts = explode('/', $relativePath);
if (count($parts) != 2) {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid API endpoint']);
    exit;
}

$folder = $parts[0];
$file = $parts[1];

// Chặn các request không phải file php
if (pathinfo($file, PATHINFO_EXTENSION) !== 'php') {
    http_response_code(400);
    echo json_encode(['message' => 'Invalid file requested']);
    exit;
}

// Đường dẫn file thực tế
$targetFile = __DIR__ . '/' . $folder . '/' . $file;

// Kiểm tra file tồn tại
if (!file_exists($targetFile)) {
    http_response_code(404);
    echo json_encode(['message' => 'API endpoint not found']);
    exit;
}

// Gọi file xử lý
require_once $targetFile;
