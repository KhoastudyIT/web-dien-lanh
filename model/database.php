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
    }
}