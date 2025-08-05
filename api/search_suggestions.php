<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type');

include_once '../model/sanpham.php';
include_once '../controller/controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = isset($_GET['q']) ? trim($_GET['q']) : '';
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
    
    if (strlen($query) < 2) {
        echo json_encode([]);
        exit;
    }
    
    try {
        $controller = new controller();
        $suggestions = $controller->getSearchSuggestions($query, $limit);
        
        // Format suggestions for display
        $formatted_suggestions = [];
        foreach ($suggestions as $item) {
            $formatted_suggestions[] = [
                'id' => $item['id_sp'],
                'name' => $item['Name'],
                'brand' => $item['ten_hang'],
                'category' => $item['ten_danhmuc'],
                'price' => number_format($item['Price'], 0, ',', '.') . ' ₫',
                'image' => $item['image'],
                'logo' => $item['logo_hang'],
                'sale' => $item['Sale'],
                'url' => '/project/index.php?act=chitiet&id=' . $item['id_sp']
            ];
        }
        
        echo json_encode($formatted_suggestions);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal server error']);
    }
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
}
?> 