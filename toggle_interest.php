<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$property_id = isset($_POST['property_id']) ? (int)$_POST['property_id'] : 0;

if ($user_id <= 0 || $property_id <= 0) {
    echo json_encode(["status" => "error", "message" => "Invalid user or property"]);
    exit;
}

// Check if interest already exists
$check_stmt = $conn->prepare("SELECT * FROM interested_users WHERE user_id = ? AND property_id = ?");
$check_stmt->bind_param("ii", $user_id, $property_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows > 0) {
    // Remove interest (Toggle off)
    $delete_stmt = $conn->prepare("DELETE FROM interested_users WHERE user_id = ? AND property_id = ?");
    $delete_stmt->bind_param("ii", $user_id, $property_id);
    $delete_stmt->execute();
    echo json_encode(["status" => "removed"]);
} else {
    // Add interest (Toggle on)
    $insert_stmt = $conn->prepare("INSERT INTO interested_users (user_id, property_id) VALUES (?, ?)");
    $insert_stmt->bind_param("ii", $user_id, $property_id);
    $insert_stmt->execute();
    echo json_encode(["status" => "added"]);
}

$conn->close();
?>