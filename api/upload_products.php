<?php
require_once __DIR__ . '/../model/sanpham.php';
require_once __DIR__ . '/../model/danhmuc.php';
require_once __DIR__ . '/../model/hang.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

try {
    if (!isset($_FILES['excel_file']) || $_FILES['excel_file']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Không có file được tải lên hoặc file bị lỗi');
    }

    $file = $_FILES['excel_file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];

    // Kiểm tra loại file
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $allowedExtensions = ['csv'];
    
    if (!in_array($fileExtension, $allowedExtensions)) {
        throw new Exception('Chỉ chấp nhận file CSV');
    }

    // Kiểm tra kích thước file (max 5MB)
    if ($fileSize > 5 * 1024 * 1024) {
        throw new Exception('File quá lớn. Kích thước tối đa là 5MB');
    }

    // Đọc file CSV
    $products = [];
    $errors = [];
    $successCount = 0;

    $handle = fopen($fileTmpName, 'r');
    if ($handle === false) {
        throw new Exception('Không thể đọc file CSV');
    }

    // Bỏ qua header
    $header = fgetcsv($handle);
    $rowNumber = 1;

    while (($data = fgetcsv($handle)) !== false) {
        $rowNumber++;
        
        if (count($data) < 7) {
            $errors[] = "Dòng $rowNumber: Thiếu dữ liệu";
            continue;
        }

        $product = [
            'name' => trim($data[0]),
            'price' => (int)trim($data[1]),
            'mount' => (int)trim($data[2]),
            'sale' => (int)trim($data[3]),
            'describe' => trim($data[4]),
            'category_name' => trim($data[5]),
            'brand_name' => trim($data[6])
        ];

        // Validate dữ liệu
        if (empty($product['name'])) {
            $errors[] = "Dòng $rowNumber: Tên sản phẩm không được để trống";
            continue;
        }

        if ($product['price'] <= 0) {
            $errors[] = "Dòng $rowNumber: Giá phải lớn hơn 0";
            continue;
        }

        if ($product['mount'] < 0) {
            $errors[] = "Dòng $rowNumber: Số lượng không được âm";
            continue;
        }

        $products[] = $product;
    }
    fclose($handle);

    if (empty($products)) {
        throw new Exception('Không có dữ liệu sản phẩm hợp lệ trong file');
    }

    // Import sản phẩm vào database
    $sanpham = new sanpham();
    $danhmuc = new danhmuc();
    $hang = new hang();

    foreach ($products as $index => $product) {
        try {
            // Tìm danh mục theo tên
            $categoryId = null;
            $categories = $danhmuc->getAllCategories();
            foreach ($categories as $cat) {
                if ($cat['name'] === $product['category_name']) {
                    $categoryId = $cat['id'];
                    break;
                }
            }
            
            // Tìm hãng theo tên
            $brandId = null;
            $brands = $hang->getAllBrands();
            foreach ($brands as $brand) {
                if ($brand['ten_hang'] === $product['brand_name']) {
                    $brandId = $brand['id_hang'];
                    break;
                }
            }

            // Kiểm tra xem có tìm thấy danh mục và hãng không
            if (!$categoryId) {
                $errors[] = "Dòng " . ($index + 2) . ": Không tìm thấy danh mục '" . $product['category_name'] . "'";
                continue;
            }
            
            if (!$brandId) {
                $errors[] = "Dòng " . ($index + 2) . ": Không tìm thấy hãng '" . $product['brand_name'] . "'";
                continue;
            }
            
            // Thêm sản phẩm
            if ($sanpham->addProduct(
                $product['name'],
                $product['price'],
                $product['mount'],
                $product['sale'],
                $product['describe'],
                '', // image - để trống
                $categoryId,
                $brandId
            )) {
                $successCount++;
            } else {
                $errors[] = "Dòng " . ($index + 2) . ": Không thể thêm sản phẩm";
            }
        } catch (Exception $e) {
            $errors[] = "Dòng " . ($index + 2) . ": " . $e->getMessage();
        }
    }

    $response = [
        'success' => true,
        'message' => "Đã import thành công $successCount sản phẩm",
        'total' => count($products),
        'success_count' => $successCount,
        'error_count' => count($errors),
        'errors' => $errors
    ];

    echo json_encode($response);

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 