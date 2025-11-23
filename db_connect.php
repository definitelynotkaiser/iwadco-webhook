<?php
// Database connection para sa InfinityFree MySQL
$servername = "sql312.infinityfree.com";
$username = "if0_40436879";
$password = "Danishkaiser24"; // yung password mo sa InfinityFree MySQL
$database = "if0_40436879_iwadco";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
