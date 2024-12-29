<?php
$servername = "localhost";
$username = "root"; // Your database username
$password = "Ktanooj@2004"; // Your database password
$dbname = "bulk_email_system"; // Your database name

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

