<?php
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
        return $xl->readitem('SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
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
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
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
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
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
        $result = $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
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
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
                             FROM sanpham sp 
                             LEFT JOIN danhmuc dm ON sp.id_danhmuc = dm.id 
                             LEFT JOIN hang h ON sp.id_hang = h.id_hang 
                             WHERE sp.Name LIKE '%$keyword%' 
                             OR dm.name LIKE '%$keyword%' 
                             OR h.ten_hang LIKE '%$keyword%' 
                             ORDER BY sp.id_sp DESC");
    }

    // Lấy sản phẩm nổi bật (có sale)
    public function getSanphamNoiBat() {
        $db = new database();
        $conn = $db->connection_database();
        $xl = new xl_data($conn);
        return $xl->readitem("SELECT sp.*, dm.name as ten_danhmuc, h.ten_hang 
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
}
?> 