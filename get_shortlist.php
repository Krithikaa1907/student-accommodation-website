<?php
header('Content-Type: application/json');
require_once 'db.php';

$user_id = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;

$stmt = $conn->prepare("
    SELECT p.* FROM properties p
    JOIN interested_users iu ON p.id = iu.property_id
    WHERE iu.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$shortlists = [];
while ($row = $result->fetch_assoc()) {
    $shortlists[] = $row;
}

echo json_encode(["status" => "success", "data" => $shortlists]);
$conn->close();
?>