<?php
// Database connection
$host = 'localhost';
$dbname = 'churchweb';
$username = 'root';
$password = '';

$conn = new mysqli("localhost", "root", "", "churchweb");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>