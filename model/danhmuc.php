<?php

    include "xl_data.php";
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



    }