<?php
$host = "localhost";
$db_name = "php-beginner-crud";
$username = "root";
$password = "";

try {
    $conn = new PDO("mysql:host={$host};dbname={$db_name}", $username, $password);
} catch (PDOException $exception) {
    echo "Connection Error: " . $exception->getMessage();
}
