<?php
require_once 'database.php';

class hang {
    private $id_hang;
    private $ten_hang;
    private $logo_hang;

    // Getters
    public function getId_hang() { return $this->id_hang; }
    public function getTen_hang() { return $this->ten_hang; }
    public function getLogo_hang() { return $this->logo_hang; }

    // Setters
    public function setId_hang($id_hang) { $this->id_hang = $id_hang; }
    public function setTen_hang($ten_hang) { $this->ten_hang = $ten_hang; }
    public function setLogo_hang($logo_hang) { $this->logo_hang = $logo_hang; }

    // Lấy tất cả hãng
    public function getAllBrands() {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("SELECT * FROM hang ORDER BY ten_hang");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Lấy hãng theo ID
    public function getBrandById($id) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("SELECT * FROM hang WHERE id_hang = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
    }



    // Cập nhật hãng
    public function updateBrand($id, $ten_hang, $logo_hang = '') {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("UPDATE hang SET ten_hang = ?, logo_hang = ? WHERE id_hang = ?");
            return $stmt->execute([$ten_hang, $logo_hang, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    // Xóa hãng
    public function deleteBrand($id) {
        try {
            $db = new database();
            $conn = $db->connection_database();
            
            $stmt = $conn->prepare("DELETE FROM hang WHERE id_hang = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }

            // Đếm số sản phẩm theo hãng
        public function getProductCount($brand_id) {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("SELECT COUNT(*) FROM sanpham WHERE id_hang = ?");
                $stmt->execute([$brand_id]);
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                return 0;
            }
        }


    }
    ?> 