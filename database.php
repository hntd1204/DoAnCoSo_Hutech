<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "swiftship";

// Kết nối CSDL
$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("Kết nối CSDL thất bại: " . $conn->connect_error);
}
