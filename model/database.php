<?php 
    if (!class_exists('database')) {
    class database{
        private $servername = "localhost";
        private $username="root";
        private $password="";
        private $databasename="dienlanh_shop";// Tên database
        protected $conn = null;

    function connection_database(){
        try{         $this->conn = new PDO("mysql:host=$this->servername;dbname=$this->databasename",
                            $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);   
        }catch(PDOException $e){   
            throw $e;
        }
            return $this->conn;
        }

    // Thêm các method cần thiết cho DonHang class
    public function beginTransaction() {
        $this->conn = $this->connection_database();
        return $this->conn->beginTransaction();
    }

    public function commit() {
        if ($this->conn) {
            return $this->conn->commit();
        }
        return false;
    }

    public function rollback() {
        if ($this->conn) {
            return $this->conn->rollback();
        }
        return false;
    }

    public function prepare($sql) {
        if (!$this->conn) {
            $this->conn = $this->connection_database();
        }
        return $this->conn->prepare($sql);
    }

    public function lastInsertId() {
        if ($this->conn) {
            return $this->conn->lastInsertId();
        }
        return null;
    }

    public function query($sql) {
        if (!$this->conn) {
            $this->conn = $this->connection_database();
        }
        return $this->conn->query($sql);
    }
}
}