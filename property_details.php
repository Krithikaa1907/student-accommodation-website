<?php
require_once 'db.php';

$property_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch Property Details
$stmt = $conn->prepare("SELECT * FROM properties WHERE id = ?");
$stmt->bind_param("i", $property_id);
$stmt->execute();
$property = $stmt->get_result()->fetch_assoc();

if (!$property) {
    die("Property not found!");
}

// Fetch Amenities for this property
$amenities_stmt = $conn->prepare("
    SELECT a.name FROM amenities a
    JOIN property_amenities pa ON a.id = pa.amenity_id
    WHERE pa.property_id = ?
");
$amenities_stmt->bind_param("i", $property_id);
$amenities_stmt->execute();
$amenities_result = $amenities_stmt->get_result();

$amenities = [];
while ($row = $amenities_result->fetch_assoc()) {
    $amenities[] = $row['name'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['name']); ?> - Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-house-user me-2"></i>CampusStay</a>
            <a href="index.php" class="btn btn-light btn-sm">Back to Listings</a>
        </div>
    </nav>

    <div class="container my-5">
        <div class="row">
            <!-- Property Image & Basic Info -->
            <div class="col-md-7 mb-4">
                <div class="card shadow-sm border-0">
                    <img src="<?php echo !empty($property['image_url']) ? $property['image_url'] : 'https://via.placeholder.com/600x400'; ?>" class="card-img-top img-fluid rounded-top" alt="Property Image">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-info text-dark fs-6"><?php echo htmlspecialchars($property['gender']); ?></span>
                            <span class="text-warning fw-bold fs-5"><i class="fa-solid fa-star"></i> <?php echo htmlspecialchars($property['rating']); ?></span>
                        </div>
                        <h2 class="card-title fw-bold"><?php echo htmlspecialchars($property['name']); ?></h2>
                        <p class="text-muted"><i class="fa-solid fa-location-dot me-1"></i> <?php echo htmlspecialchars($property['city']); ?></p>
                        <h4 class="text-primary fw-bold mb-3">₹<?php echo htmlspecialchars($property['price']); ?> <small class="fs-6 text-muted">/ month</small></h4>
                        
                        <hr>

                        <h5>Description</h5>
                        <p class="text-secondary"><?php echo !empty($property['description']) ? htmlspecialchars($property['description']) : 'No description available for this property.'; ?></p>
                    </div>
                </div>
            </div>

            <!-- Amenities & Action Sidebar -->
            <div class="col-md-5">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Amenities</h5>
                        <?php if (count($amenities) > 0): ?>
                            <ul class="list-group list-group-flush mb-3">
                                <?php foreach ($amenities as $amenity): ?>
                                    <li class="list-group-content list-group-item bg-transparent ps-0">
                                        <i class="fa-solid fa-check text-success me-2"></i> <?php echo htmlspecialchars($amenity); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <p class="text-muted">No amenities listed.</p>
                        <?php endif; ?>

                        <!-- AJAX Interest Toggle Button -->
                        <button id="interestBtn" class="btn btn-outline-danger w-100 py-2 fw-semibold" data-property-id="<?php echo $property['id']; ?>">
                            <i class="fa-regular fa-heart me-2"></i> Shortlist / Mark as Interested
                        </button>
                        <div id="interestAlert" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- AJAX Script for Interest Toggle -->
    <script>
        document.getElementById("interestBtn").addEventListener("click", function() {
            const propertyId = this.getAttribute("data-property-id");
            const btn = this;
            const alertBox = document.getElementById("interestAlert");

            // For testing, hardcoding user_id = 1 (In real app, session user_id use pannuvel)
            const userId = 1;

            btn.disabled = true;

            fetch("toggle_interest.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                },
                body: `user_id=${userId}&property_id=${propertyId}`
            })
            .then(res => res.json())
            .then(data => {
                btn.disabled = false;
                if (data.status === "added") {
                    btn.classList.remove("btn-outline-danger");
                    btn.classList.add("btn-danger");
                    btn.innerHTML = `<i class="fa-solid fa-heart me-2"></i> Interested`;
                    alertBox.innerHTML = `<div class="alert alert-success alert-dismissible py-2 fs-6 mt-2">Added to your shortlisted properties!</div>`;
                } else if (data.status === "removed") {
                    btn.classList.remove("btn-danger");
                    btn.classList.add("btn-outline-danger");
                    btn.innerHTML = `<i class="fa-regular fa-heart me-2"></i> Shortlist / Mark as Interested`;
                    alertBox.innerHTML = `<div class="alert alert-info alert-dismissible py-2 fs-6 mt-2">Removed from shortlist.</div>`;
                } else {
                    alertBox.innerHTML = `<div class="alert alert-danger py-2 fs-6 mt-2">${data.message}</div>`;
                }
            })
            .catch(err => {
                btn.disabled = false;
                alertBox.innerHTML = `<div class="alert alert-danger py-2 fs-6 mt-2">Error processing request.</div>`;
            });
        });
    </script>
</body>
</html>