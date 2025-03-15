<?php
class Database {
    public $conn;

    public function __construct() {
        $config = require $_SERVER['DOCUMENT_ROOT'].'/env.php';
        $this->conn = new mysqli($config["DB_HOST"],$config["DB_USER"],$config["DB_PASS"],$config["DB_NAME"] );
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }
}
?>

