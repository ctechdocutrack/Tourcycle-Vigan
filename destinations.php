<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<style>
    /* Destination Cards */
    .destination-item .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        border: 1px solid #E2E8F0 !important;
    }

    .destination-item .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(56, 189, 248, 0.15) !important;
    }
</style>

<!-- Destinations Page -->
<section id="map" class="position-relative min-vh-100 px-4 px-lg-5 py-4 pb-5 mb-5">

    <!-- Page Header -->
    <div class="mb-4">
        <span class="fw-semibold text-uppercase small" style="color: #0284C7;">Explore Vigan</span>
        <h2 class="fw-bold mt-1" style="color: #1E293B;">Discover Destinations</h2>
        <p class="text-muted mb-0">Explore the beauty, history, and heritage of Vigan City.</p>
    </div>

    <!-- Search and Filter -->
    <div class="row g-3 align-items-center mb-4">

        <div class="col-12 col-md-5">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="bi bi-search" style="color: #0284C7;"></i>
                </span>
                <input type="text" id="destinationSearch"
                    class="form-control border-start-0" style="border-color: #E2E8F0;"
                    placeholder="Search destinations...">
            </div>
        </div>

        <div class="col-12 col-md-7">
            <div class="d-flex flex-wrap gap-2" id="destinationFilters">
                <button class="btn rounded-pill active text-white" style="background-color: #38BDF8; border-color: #38BDF8;" data-filter="all">All</button>
            </div>
        </div>

    </div>

    <!-- Destination Grid -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-1" style="color: #1E293B;">Popular Destinations</h4>
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

