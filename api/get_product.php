<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../model/sanpham.php';

if (!isset($_GET['id'])) {
    echo json_encode(['success' => false, 'message' => 'Thiếu ID sản phẩm']);
    exit();
}

$id = intval($_GET['id']);
$sanpham = new sanpham();

try {
    $product = $sanpham->getProductById($id);
    
    if ($product) {
        echo json_encode(['success' => true, 'product' => $product]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Không tìm thấy sản phẩm']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Lỗi: ' . $e->getMessage()]);
}
?> 