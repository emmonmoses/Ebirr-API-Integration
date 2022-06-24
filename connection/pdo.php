<?php
// DEVELOPMENT
$host = "127.0.0.1";
$username = "root";
$password = "";
$db_name = "gibsoncollege";

try {
    $conn_pdo = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);

}
catch (PDOException $exception) {
    echo "Connection error: " . $exception->getMessage();
}
?>