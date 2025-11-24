<?php 
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql_table = "CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(225) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

if ($conn->query($sql_table) === FALSE) {
    echo "Error creating table: " . $conn->error;
}

?>