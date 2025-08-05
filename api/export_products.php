<?php
require_once __DIR__ . '/../model/sanpham.php';

$format = $_GET['format'] ?? 'excel';
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sanpham = new sanpham();
$products = $sanpham->getAllProducts($search, $category);

if ($format === 'excel') {
    // Xuất Excel
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment; filename="danh_sach_san_pham.xls"');
    
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>ID</th>';
    echo '<th>Tên Sản Phẩm</th>';
    echo '<th>Giá</th>';
    echo '<th>Số Lượng</th>';
    echo '<th>Giảm Giá (%)</th>';
    echo '<th>Danh Mục</th>';
    echo '<th>Mô Tả</th>';
    echo '</tr>';
    
    foreach ($products as $product) {
        echo '<tr>';
        echo '<td>' . ($product['id_sp'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($product['Name'] ?? '') . '</td>';
        echo '<td>' . number_format($product['Price'] ?? 0) . ' VNĐ</td>';
        echo '<td>' . ($product['Mount'] ?? '') . '</td>';
        echo '<td>' . ($product['Sale'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($product['ten_danhmuc'] ?? '') . '</td>';
        echo '<td>' . htmlspecialchars($product['Decribe'] ?? '') . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
} elseif ($format === 'pdf') {
    // Xuất PDF (đơn giản bằng HTML)
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="danh_sach_san_pham.pdf"');
    
    echo '<html>';
    echo '<head><title>Danh Sách Sản Phẩm</title></head>';
    echo '<body>';
    echo '<h1>Danh Sách Sản Phẩm</h1>';
    echo '<table border="1" style="width:100%; border-collapse: collapse;">';
    echo '<tr style="background-color: #f0f0f0;">';
    echo '<th style="border: 1px solid #000; padding: 8px;">ID</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Tên Sản Phẩm</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Giá</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Số Lượng</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Giảm Giá (%)</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Danh Mục</th>';
    echo '<th style="border: 1px solid #000; padding: 8px;">Mô Tả</th>';
    echo '</tr>';
    
    foreach ($products as $product) {
        echo '<tr>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . ($product['id_sp'] ?? '') . '</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($product['Name'] ?? '') . '</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . number_format($product['Price'] ?? 0) . ' VNĐ</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . ($product['Mount'] ?? '') . '</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . ($product['Sale'] ?? '') . '</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($product['ten_danhmuc'] ?? '') . '</td>';
        echo '<td style="border: 1px solid #000; padding: 8px;">' . htmlspecialchars($product['Decribe'] ?? '') . '</td>';
        echo '</tr>';
    }
    
    echo '</table>';
    echo '</body>';
    echo '</html>';
} else {
    echo 'Định dạng không được hỗ trợ';
}
?> 