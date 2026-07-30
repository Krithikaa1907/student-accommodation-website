<?php
header('Content-Type: application/json');
require_once 'db.php';

// Filter parameters get panrom
$city   = isset($_GET['city']) ? trim($_GET['city']) : '';
$gender = isset($_GET['gender']) ? trim($_GET['gender']) : '';
$max_price = isset($_GET['max_price']) && $_GET['max_price'] != '' ? (float)$_GET['max_price'] : 0;

// Dynamic SQL query build panrom
$sql = "SELECT * FROM properties WHERE 1=1";
$params = [];
$types = "";

if (!empty($city)) {
    $sql .= " AND city = ?";
    $params[] = $city;
    $types .= "s";
}

if (!empty($gender)) {
    $sql .= " AND (gender = ? OR gender = 'Unisex')";
    $params[] = $gender;
    $types .= "s";
}

if ($max_price > 0) {
    $sql .= " AND price <= ?";
    $params[] = $max_price;
    $types .= "d";
}

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

$properties = [];
while ($row = $result->fetch_assoc()) {
    $properties[] = $row;
}

// JSON Response-a anuppudhu
echo json_encode([
    "status" => "success",
    "data" => $properties
]);

$stmt->close();
$conn->close();
?>