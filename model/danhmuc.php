<?php

    include "xl_data.php";
    include "database.php";

class  danhmuc {
        private $id_dm = 0; // Thuộc tính ID danh mục
        private $Name = ""; // Thuộc tính tên danh mục

        public function setId($id_dm){
            return $this->id_dm = $id_dm;
        }
        public function getId(){
            return $this->id_dm;
        }
        public function setName($Name){
            return  $this->Name = $Name;
        }
        public function getName(){
            return  $this->Name;
        }

        public function getDS_Danhmuc(){
            $xl = new xl_data();
            $sql = "SELECT * FROM `danhmuc`";
            $results = $xl->readitem($sql);
            return $results;
        }
        public function themDM(danhmuc $dm){
            $xl = new xl_data();
            // Cách viết câu SQL
            $sql = "INSERT INTO `danhmuc` (`id`, `name`) 
            VALUES (NULL, '".$dm->getName()."')";
            // Gọi hàm thực thi câu SQL trong xl_data
            $xl->execute_item($sql);
        }
        public function xoadm(danhmuc $dm){
            $xl = new xl_data();
            $sql = "DELETE FROM `danhmuc` 
            WHERE `danhmuc`.`id` = ". $dm->getId();
            $xl->execute_item($sql);
        }

        // Thêm các phương thức mới cho admin
        public function getAllCategories() {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("SELECT * FROM danhmuc ORDER BY id");
                $stmt->execute();
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return [];
            }
        }

        public function addCategory($name) {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("INSERT INTO danhmuc (name) VALUES (?)");
                return $stmt->execute([$name]);
            } catch (PDOException $e) {
                return false;
            }
        }

        public function deleteCategory($id) {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("DELETE FROM danhmuc WHERE id = ?");
                return $stmt->execute([$id]);
            } catch (PDOException $e) {
                return false;
            }
        }

        public function getTotalCategories() {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("SELECT COUNT(*) FROM danhmuc");
                $stmt->execute();
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                return 0;
            }
        }

        public function getProductCount($category_id) {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("SELECT COUNT(*) FROM sanpham WHERE id_danhmuc = ?");
                $stmt->execute([$category_id]);
                return $stmt->fetchColumn();
            } catch (PDOException $e) {
                return 0;
            }
        }



        public function getCategoryById($id) {
            try {
                $db = new database();
                $conn = $db->connection_database();
                
                $stmt = $conn->prepare("SELECT * FROM danhmuc WHERE id = ?");
                $stmt->execute([$id]);
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                return null;
            }
        }
    }
    ?>