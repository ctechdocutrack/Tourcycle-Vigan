
<?php 
include 'includes/admin-auth.php';
include 'includes/admin-only.php';
include 'includes/admin-header.php'; 
?>
<?php include 'includes/flash-alert.php'; ?>
<div class="container-fluid">
    <div class="row">

        <?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-10 col-lg-10 px-3 px-md-4 py-4">

            <!-- Page Heading -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Destinations</h3>
                    <p class="text-muted mb-0">
                        Manage all tourist destinations in Tourcycle Vigan.
                    </p>
                </div>

                <a href="add-destination.php" class="btn btn-success">
                    <i class="bi bi-plus-lg me-2"></i>
                    Add Destination
                </a>
            </div>

            <!-- Statistics -->
            <?php
            $destinations = $_SESSION['destinations'] ?? [];

            $totalDestinations = count($destinations);
            $activeDestinations = 0;
            $inactiveDestinations = 0;

            reset($destinations);

            while ($row = current($destinations)) {

                if ($row['status'] === 'Active') {
                    $activeDestinations++;
                } elseif ($row['status'] === 'Inactive') {
                    $inactiveDestinations++;
                }

                next($destinations);
            }
            ?>
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted fw-semibold">Total Destinations</small>
                                    <h3 class="fw-bold mb-0 mt-2"><?php echo $totalDestinations; ?></h3>
                                </div>
                                <span class="bg-success-subtle text-success rounded-3 p-3">
                                    <i class="bi bi-geo-alt fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted fw-semibold">Active Destinations</small>
                                    <h3 class="fw-bold mb-0 mt-2"><?php echo $activeDestinations; ?></h3>
                                </div>
                                <span class="bg-primary-subtle text-primary rounded-3 p-3">
                                    <i class="bi bi-check-circle fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <small class="text-muted fw-semibold">Inactive Destinations</small>
                                    <h3 class="fw-bold mb-0 mt-2"><?php echo $inactiveDestinations; ?></h3>
                                </div>
                                <span class="bg-danger-subtle text-danger rounded-3 p-3">
                                    <i class="bi bi-eye-slash fs-4"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Destinations Table -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white border-0 p-3">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">

                        <h5 class="fw-bold mb-0">All Destinations</h5>

                        <div class="d-flex w-50 gap-2">

                            <!-- Search -->
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="search" id="destinationSearch"
                                    class="form-control"
                                    placeholder="Search destinations...">
                            </div>

                            <!-- Category Filter -->
                            <select id="categoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                <option value="Historical Site">Historical Site</option>
                                <option value="Historical Landmark">Historical Landmark</option>
                                <option value="Church">Church</option>
                            </select>

                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Destinations List -->
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-3 g-3" id="destinationsList">

                    <?php
                    $destinations = $_SESSION['destinations'] ?? [];

                    if (!empty($destinations)) {

                        usort($destinations, function ($first, $second) {
                            return strtotime($second['created_at']) <=> strtotime($first['created_at']);
                        });

                        reset($destinations);

                        while ($row = current($destinations)) {

                            $id = $row['destinations_id'];
                            $name = $row['destination_name'];
                            $category = $row['category'];
                            $status = $row['status'];
                            $image = $row['image'];
                            $latitude = $row['latitude'];
                            $longitude = $row['longitude'];

                            $locationKey = $latitude . '_' . $longitude;

                            $location = $_SESSION['destination_locations'][$locationKey] ?? "Location unavailable";
                            echo '<div class="col">
                                    <div class="card border-0 shadow-sm destination-item h-100" data-category="' . htmlspecialchars($category) . '">
                                        <div class="card-body p-3">
                                            <img src="' . htmlspecialchars($image) . '" class="w-100 rounded-3 object-fit-cover mb-3" height="170" alt="' . htmlspecialchars($name) . '">

                                            <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                                                <h6 class="fw-bold mb-0">' . htmlspecialchars($name) . '</h6>
                                                <span class="badge ' . ($status === 'Active' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger') . '">' . htmlspecialchars($status) . '</span>
                                            </div>

                                            <p class="text-muted small mb-2">
                                                <i class="bi bi-geo-alt me-1"></i> ' . htmlspecialchars($location) . '
                                            </p>

                                            <span class="badge bg-light text-secondary border">' . htmlspecialchars($category) . '</span>

                                            <div class="d-flex gap-2 mt-3">
                                                <a href="edit-destinations.php?id=' . urlencode($id) . '" class="btn btn-light border btn-sm flex-fill">
                                                    <i class="bi bi-pencil-square me-1"></i> Edit
                                                </a>
                                                <form action="controller/deleteDestinationController.php" method="POST" class="flex-fill" onsubmit="return confirm(&quot;Are you sure you want to delete this destination?&quot;);">

                                                <input type="hidden" name="destination_id" value="' . htmlspecialchars($id) . '">

                                                <button type="submit" class="btn btn-light border text-danger btn-sm w-100">
                                                    <i class="bi bi-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>';

                            next($destinations);
                        }

                    } else {

                        echo '<div class="col-12 text-center py-5">
                                <i class="bi bi-search fs-1 text-muted"></i>
                                <h6 class="fw-bold mt-3">No destinations found</h6>
                                <p class="text-muted small mb-0">Try another search or category.</p>
                            </div>';
                    }
                    ?>
                    </div>
                </div>
            </div>

        </main>

    </div>
</div>

<!-- Search and Filter -->
<script>

document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("destinationSearch");
    const categoryFilter = document.getElementById("categoryFilter");
    const destinationCards = document.querySelectorAll(".destination-item");
    const destinationsList = document.getElementById("destinationsList");

    // No Results Message
    const noResults = document.createElement("div");
    noResults.className = "col-12 text-center py-5 d-none";
    noResults.innerHTML = `
        <i class="bi bi-search fs-1 text-muted"></i>
        <h6 class="fw-bold mt-3">No destinations found</h6>
        <p class="text-muted small mb-0">Try another search or category.</p>
    `;
    destinationsList.appendChild(noResults);

    function filterDestinations() {

        const searchValue = searchInput.value.toLowerCase().trim();
        const categoryValue = categoryFilter.value;
        let visibleCount = 0;

        destinationCards.forEach(function (card) {

            const destinationName = card.textContent.toLowerCase();
            const category = card.dataset.category;

            const matchesSearch = destinationName.includes(searchValue);
            const matchesCategory = categoryValue === "" || category === categoryValue;

            const isVisible = matchesSearch && matchesCategory;

            card.closest(".col").classList.toggle("d-none", !isVisible);

            if (isVisible) {
                visibleCount++;
            }

        });

        noResults.classList.toggle("d-none", visibleCount > 0);

    }

    // Search
    searchInput.addEventListener("input", filterDestinations);

    // Category Filter
    categoryFilter.addEventListener("change", filterDestinations);

    // Delete Confirmation
    document.querySelectorAll(".deleteDestination").forEach(function (button) {

        button.addEventListener("click", function () {

            if (confirm("Are you sure you want to delete this destination?")) {
                alert("Delete functionality will be connected to the database.");
            }

        });

    });

});

</script>

<?php include 'includes/admin-footer.php'; ?>
