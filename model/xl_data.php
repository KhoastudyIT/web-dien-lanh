<?php
include "database.php";
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
}