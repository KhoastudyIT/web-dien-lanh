<?php 
    if (!class_exists('database')) {
    class database{
        private $servername = "localhost";
        private $username="root";
        private $password="";
        private $databasename="dienlanh_shop";// Tên database
        protected $conn = null;

    function connection_database(){
        try{
            $conn = new PDO("mysql:host=$this->servername;dbname=$this->databasename",
                            $this->username, $this->password);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);   
        }catch(PDOException $e){   
            throw $e;
        }
            return $conn;
        }

    // Thêm các method cần thiết cho DonHang class
    public function beginTransaction() {
        return $this->connection_database()->beginTransaction();
    }

    public function commit() {
        return $this->connection_database()->commit();
    }

    public function rollback() {
        return $this->connection_database()->rollback();
    }

    public function prepare($sql) {
        return $this->connection_database()->prepare($sql);
    }

    public function lastInsertId() {
        return $this->connection_database()->lastInsertId();
    }

    public function query($sql) {
        return $this->connection_database()->query($sql);
    }
}
}