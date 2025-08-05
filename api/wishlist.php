<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

include_once '../model/wishlist.php';
include_once '../helpers/jwt_helper.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

// Kiểm tra đăng nhập
$currentUser = getCurrentUser();
if (!$currentUser) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Vui lòng đăng nhập']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    
    $wishlist = new Wishlist();
    
    switch ($action) {
        case 'add':
            $productId = $input['product_id'] ?? 0;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
                exit();
            }
            
            $result = $wishlist->addToWishlist($currentUser['id_user'], $productId);
            echo json_encode($result);
            break;
            
        case 'remove':
            $productId = $input['product_id'] ?? 0;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
                exit();
            }
            
            $result = $wishlist->removeFromWishlist($currentUser['id_user'], $productId);
            echo json_encode($result);
            break;
            
        case 'add_to_cart':
            $productId = $input['product_id'] ?? 0;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
                exit();
            }
            
            $result = $wishlist->addWishlistToCart($currentUser['id_user'], $productId);
            echo json_encode($result);
            break;
            
        case 'clear':
            $result = $wishlist->clearWishlist($currentUser['id_user']);
            echo json_encode($result);
            break;
            
        case 'check':
            $productId = $input['product_id'] ?? 0;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
                exit();
            }
            
            $isInWishlist = $wishlist->isInWishlist($currentUser['id_user'], $productId);
            echo json_encode(['success' => true, 'in_wishlist' => $isInWishlist]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            break;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = $_GET['action'] ?? '';
    
    $wishlist = new Wishlist();
    
    switch ($action) {
        case 'count':
            $count = $wishlist->getWishlistCount($currentUser['id_user']);
            echo json_encode(['success' => true, 'count' => $count]);
            break;
            
        case 'list':
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $items = $wishlist->getUserWishlist($currentUser['id_user'], $limit, $offset);
            echo json_encode(['success' => true, 'items' => $items]);
            break;
            
        case 'check':
            $productId = $_GET['product_id'] ?? 0;
            if (!$productId) {
                echo json_encode(['success' => false, 'message' => 'Thiếu thông tin sản phẩm']);
                exit();
            }
            
            $isInWishlist = $wishlist->isInWishlist($currentUser['id_user'], $productId);
            echo json_encode(['success' => true, 'in_wishlist' => $isInWishlist]);
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Hành động không hợp lệ']);
            break;
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?> 