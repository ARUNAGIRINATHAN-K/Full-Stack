<?php
$servername = "localhost"; // Your server name, usually localhost for XAMPP
$username = "root";      // Your MySQL username (default is root for XAMPP)
$password = "";          // Your MySQL password (default is empty for XAMPP)
$dbname = "mic_database"; // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>