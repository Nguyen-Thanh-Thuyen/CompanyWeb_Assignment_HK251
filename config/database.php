<?php
class Database {
    private $host = "localhost";
    private $db_name = "shop"; 
    private $username = "root";
    private $password = "VTnL74123!!!";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
            // Use setAttribute for names/charset for consistency with other attributes
            $this->conn->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
            
        } catch(PDOException $exception) {
            // 💡 CRITICAL CHANGE: Stop execution when the connection fails.
            // In a production environment, you would log this error instead of echoing.
            die("Lỗi kết nối CSDL: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
