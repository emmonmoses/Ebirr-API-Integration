<?php
class Database
{
  // DEVELOPMENT
  private $host = "127.0.0.1";
  private $database_name = "gibsoncollege";
  private $username = "root";
  private $password = "";
  public $conn_db;

  public function getConnection()
  {
    $this->conn_db = null;
    try {
      $this->conn_db = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->database_name, $this->username, $this->password);
      $this->conn_db->exec("set names utf8");
    }
    catch (PDOException $exception) {
      echo "Database could not be connected: " . $exception->getMessage();
    }
    return $this->conn_db;
  }
}

?>