<?php
require_once 'database.php';
require_once 'xl_data.php';
class sanpham {
    private $id_sp;
    private $Name;
    private $Price;
    private $Date_import;
    private $Viewsp;
    private $Decribe;
    private $Mount;
    private $Sale;
    private $image;
    private $id_danhmuc;
    private $id_hang;

    // Getters
    public function getId_sp() { return $this->id_sp; }
    public function getName() { return $this->Name; }
    public function getPrice() { return $this->Price; }
    public function getDate_import() { return $this->Date_import; }
    public function getViewsp() { return $this->Viewsp; }
    public function getDecribe() { return $this->Decribe; }
    public function getMount() { return $this->Mount; }
    public function getSale() { return $this->Sale; }
    public function getImage() { return $this->image; }
    public function getId_danhmuc() { return $this->id_danhmuc; }
    public function getId_hang() { return $this->id_hang; }

    // Setters
    public function setId_sp($id_sp) { $this->id_sp = $id_sp; }
    public function setName($Name) { $this->Name = $Name; }
    public function setPrice($Price) { $this->Price = $Price; }
    public function setDate_import($Date_import) { $this->Date_import = $Date_import; }
    public function setViewsp($Viewsp) { $this->Viewsp = $Viewsp; }
    public function setDecribe($Decribe) { $this->Decribe = $Decribe; }
    public function setMount($Mount) { $this->Mount = $Mount; }
    public function setSale($Sale) { $this->Sale = $Sale; }
    public function setImage($image) { $this->image = $image; }
    public function setId_danhmuc($id_danhmuc) { $this->id_danhmuc = $id_danhmuc; }
    public function setId_hang($id_hang) { $this->id_hang = $id_hang; }

    // Lấy tất cả sản phẩm
    public function getDS_Sanpham() {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem('SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             ORDER BY sp.id_sp DESC');
    }

    // Lấy sản phẩm theo danh mục
    public function getDS_SanphamByDanhmuc($id_danhmuc) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             WHERE sp.id_danhmuc = $id_danhmuc 
                             ORDER BY sp.id_sp DESC");
    }

    // Lấy sản phẩm theo hãng
    public function getDS_SanphamByHang($id_hang) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             WHERE sp.id_hang = $id_hang 
                             ORDER BY sp.id_sp DESC");
    }

    // Lấy sản phẩm theo ID
    public function getSanphamById($id_sp) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        $result = $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                                FROM sanpham sp 
                                LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                                LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                                WHERE sp.id_sp = $id_sp");
        return $result[0] ?? null;
    }

    // Tìm kiếm sản phẩm
    public function searchSanpham($keyword) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        $keyword = addslashes($keyword);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             WHERE sp.Name LIKE '%$keyword%' 
                                OR sp.Decribe LIKE '%$keyword%'
                                OR dm.name LIKE '%$keyword%' 
                                OR h.ten_hang LIKE '%$keyword%'
                             ORDER BY sp.Viewsp DESC");
    }

    // Lấy search suggestions
    public function getSearchSuggestions($keyword, $limit = 5) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        $keyword = addslashes($keyword);
        
        $sql = "SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                FROM sanpham sp 
                LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                WHERE sp.Name LIKE '%$keyword%' 
                   OR sp.Decribe LIKE '%$keyword%'
                   OR dm.name LIKE '%$keyword%' 
                   OR h.ten_hang LIKE '%$keyword%'
                   OR LOWER(sp.Name) LIKE LOWER('%$keyword%')
                   OR LOWER(sp.Decribe) LIKE LOWER('%$keyword%')
                   OR LOWER(dm.name) LIKE LOWER('%$keyword%')
                   OR LOWER(h.ten_hang) LIKE LOWER('%$keyword%')
                ORDER BY 
                    CASE 
                        WHEN sp.Name LIKE '$keyword%' THEN 1
                        WHEN sp.Name LIKE '%$keyword%' THEN 2
                        WHEN h.ten_hang LIKE '%$keyword%' THEN 3
                        WHEN dm.name LIKE '%$keyword%' THEN 4
                        ELSE 5
                    END,
                    sp.Viewsp DESC
                LIMIT $limit";
        
        return $xl->readitem($sql);
    }

    // Lấy sản phẩm nổi bật (có sale)
    public function getSanphamNoiBat() {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             WHERE sp.Sale > 0 
                             ORDER BY sp.Sale DESC 
                             LIMIT 8");
    }

    // Lấy sản phẩm mới nhất
    public function getSanphamMoiNhat() {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             ORDER BY sp.Date_import DESC 
                             LIMIT 8");
    }

    // Cập nhật lượt xem
    public function updateViewsp($id_sp) {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        $xl->execute_item("UPDATE sanpham SET Viewsp = Viewsp + 1 WHERE id_sp = $id_sp");
    }

    // Tính giá sau giảm giá
    public function getGiaSauGiam($price, $sale) {
        if ($sale > 0) {
            return $price - ($price * $sale / 100);
        }
        return $price;
    }

    // Format giá tiền
    public function formatPrice($price) {
        return number_format($price, 0, ',', '.') . ' ₫';
    }

    // Thêm các phương thức mới cho admin

    public function addProduct($name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("INSERT INTO sanpham (Name, Price, Mount, Sale, Decribe, image, id_danhmuc, id_hang, Date_import, Viewsp) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), 0)");
            return $stmt->execute([$name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function deleteProduct($id) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("DELETE FROM sanpham WHERE id_sp = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getTotalProducts($search = '', $category = '') {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $where_conditions = [];
            $params = [];
            
            if (!empty($search)) {
                $where_conditions[] = "Name LIKE ?";
                $params[] = "%$search%";
            }
            
            if (!empty($category)) {
                $where_conditions[] = "id_danhmuc = ?";
                $params[] = $category;
            }
            
            $where_clause = '';
            if (!empty($where_conditions)) {
                $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
            }
            
            $sql = "SELECT COUNT(*) FROM sanpham $where_clause";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getProductsWithPagination($search = '', $category = '', $offset = 0, $limit = 10) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $where_conditions = [];
            $params = [];
            
            if (!empty($search)) {
                $where_conditions[] = "sp.Name LIKE ?";
                $params[] = "%$search%";
            }
            
            if (!empty($category)) {
                $where_conditions[] = "sp.id_danhmuc = ?";
                $params[] = $category;
            }
            
            $where_clause = '';
            if (!empty($where_conditions)) {
                $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
            }
            
            $sql = "SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                    FROM sanpham sp 
                    LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                    LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                    $where_clause 
                    ORDER BY sp.id_sp DESC 
                    LIMIT $offset, $limit";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllProducts($search = '', $category = '') {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $where_conditions = [];
            $params = [];
            
            if (!empty($search)) {
                $where_conditions[] = "sp.Name LIKE ?";
                $params[] = "%$search%";
            }
            
            if (!empty($category)) {
                $where_conditions[] = "sp.id_danhmuc = ?";
                $params[] = $category;
            }
            
            $where_clause = '';
            if (!empty($where_conditions)) {
                $where_clause = 'WHERE ' . implode(' AND ', $where_conditions);
            }
            
            $sql = "SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                    FROM sanpham sp 
                    LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                    LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                    $where_clause 
                    ORDER BY sp.id_sp DESC";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getProductById($id) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $sql = "SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang, h.logo_hang 
                    FROM sanpham sp 
                    LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                    LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                    WHERE sp.id_sp = ?";
            
            $stmt = $conn->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }

    public function updateProduct($id, $name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $sql = "UPDATE sanpham SET 
                    Name = ?, 
                    Price = ?, 
                    Mount = ?, 
                    Sale = ?, 
                    Decribe = ?, 
                    image = ?, 
                    id_danhmuc = ?, 
                    id_hang = ? 
                    WHERE id_sp = ?";
            
            $params = [$name, $price, $mount, $sale, $describe, $image, $id_danhmuc, $id_hang, $id];
            
            $stmt = $conn->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            return false;
        }
    }
}
?> 