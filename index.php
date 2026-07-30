<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Accommodation - Property Listings</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#"><i class="fa-solid => fa-house-user me-2"></i>CampusStay</a>
        </div>
    </nav>

    <div class="container my-4">
        <h2 class="mb-4 text-center fw-bold">Find Your Ideal Accommodation</h2>

        <!-- Filter Section -->
        <div class="card shadow-sm p-3 mb-4">
            <form id="filterForm" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label fw-semibold">City</label>
                    <input type="text" id="city" class="form-control" placeholder="e.g. Chennai">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Gender</label>
                    <select id="gender" class="form-select">
                        <option value="">All</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-semibold">Max Budget (₹)</label>
                    <input type="number" id="max_price" class="form-control" placeholder="e.g. 8000">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fa-solid fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Property List Container -->
        <div id="loading" class="text-center my-5 d-none">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2 text-muted">Fetching properties...</p>
        </div>

        <div class="row" id="property-list">
            <!-- Dynamic properties will be inserted here via AJAX -->
        </div>
    </div>

    <!-- Bootstrap JS & JavaScript for AJAX -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initial Fetch
            fetchProperties();

            // Filter Form Submit
            document.getElementById("filterForm").addEventListener("submit", function(e) {
                e.preventDefault();
                fetchProperties();
            });

            function fetchProperties() {
                const city = document.getElementById("city").value;
                const gender = document.getElementById("gender").value;
                const maxPrice = document.getElementById("max_price").value;

                const loading = document.getElementById("loading");
                const propertyList = document.getElementById("property-list");

                loading.classList.remove("d-none");
                propertyList.innerHTML = "";

                // Query Parameters
                const params = new URLSearchParams({
                    city: city,
                    gender: gender,
                    max_price: maxPrice
                });

                fetch(`get_properties.php?${params.toString()}`)
                    .then(response => response.json())
                    .then(res => {
                        loading.classList.add("d-none");
                        if (res.status === "success" && res.data.length > 0) {
                            res.data.forEach(prop => {
                                const cardHTML = `
                                    <div class="col-md-4 mb-4">
                                        <div class="card h-100 shadow-sm border-0">
                                            <img src="${prop.image_url || 'https://via.placeholder.com/300x200'}" class="card-img-top" alt="${prop.name}">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <span class="badge bg-info text-dark">${prop.gender}</span>
                                                    <span class="text-warning fw-bold"><i class="fa-solid fa-star"></i> ${prop.rating}</span>
                                                </div>
                                                <h5 class="card-title fw-bold">${prop.name}</h5>
                                                <p class="card-text text-muted mb-2"><i class="fa-solid fa-location-dot"></i> ${prop.city}</p>
                                                <h6 class="text-primary fw-bold">₹${prop.price} / month</h6>
                                            </div>
                                            <div class="card-footer bg-white border-0 pb-3">
                                                <a href="property_details.php?id=${prop.id}" class="btn btn-outline-primary btn-sm w-100">View Details</a>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                propertyList.insertAdjacentHTML("beforeend", cardHTML);
                            });
                        } else {
                            propertyList.innerHTML = `<div class="col-12 text-center text-muted"><p class="fs-5">No properties found matching your criteria.</p></div>`;
                        }
                    })
                    .catch(err => {
                        loading.classList.add("d-none");
                        propertyList.innerHTML = `<div class="col-12 text-center text-danger"><p>Error fetching data. Please try again later.</p></div>`;
                    });
            }
        });
    </script>