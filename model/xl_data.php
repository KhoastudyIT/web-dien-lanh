<?php
include "database.php";
if (!class_exists('xl_data')) {
class xl_data extends database{
    // Đọc dữ liệu
    public function __construct(){}
    // Hàm thực hiện câu SQL có lấy giá trị trả về
    function readitem($sql){
        $result = $this->connection_database()->query($sql);
        $danhsach = $result->fetchAll();
        return $danhsach;
       }
    
    // Thực thi dữ liệu
    // Hàm thực hiện câu SQL không lấy giá trị trả về
    function execute_item($sql){
        $conn = new database();
        $conn->connection_database()->query($sql);
    }

    // Lấy tất cả hãng
    public function getAllBrands() {
        $result = $this->connection_database()->query("SELECT * FROM hang");
        return $result->fetchAll();
    }
}
}