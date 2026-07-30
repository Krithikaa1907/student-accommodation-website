<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "student_accommodation";

$conn = new mysqli($host, $user, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database Connection Failed: " . $conn->connect_error]));
}
?>