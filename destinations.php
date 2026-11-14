<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<!-- Destinations Page -->
<section id="map" class="position-relative min-vh-100 px-4 px-lg-5 py-4 pb-5 mb-5">

    <!-- Page Header -->
    <div class="mb-4">
        <span class="text-success fw-semibold text-uppercase small">Explore Vigan</span>
        <h2 class="fw-bold mt-1">Discover Destinations</h2>
        <p class="text-muted mb-0">Explore the beauty, history, and heritage of Vigan City.</p>
    </div>

    <!-- Search and Filter -->
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-5">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search text-muted"></i>
                </span>
                <input type="text" id="destinationSearch"
                    class="form-control border-start-0"
                    placeholder="Search destinations...">
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="d-flex flex-wrap gap-2" id="destinationFilters">
                <button class="btn btn-success rounded-pill active" data-filter="all">All</button>
            </div>
        </div>

    </div>

    <!-- Destination Grid -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1">Popular Destinations</h4>
            <p class="text-muted small mb-0">Explore more places around Vigan.</p>
        </div>
        <span class="text-muted small" id="destinationCount"></span>
    </div>

    <div class="row g-4" id="destinationGrid"></div>

    <!-- No Results -->
    <div id="noDestinations" class="text-center py-5 d-none">
        <i class="bi bi-search display-4 text-muted"></i>
        <h5 class="fw-bold mt-3">No destinations found</h5>
        <p class="text-muted">Try another search or category.</p>
    </div>

</section>

<!-- JavaScript -->
<script src="assets/js/destinations.js"></script>
<script src="assets/js/sidebar.js"></script>

<?php include 'assets/includes/footer.php'; ?>

