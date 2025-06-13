<?php
$host = "localhost";
$user = "uxgukysg8xcbd";
$password = "6imcip8yfmic";
$dbname = "dbrt9jgsrtpcgh";

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
