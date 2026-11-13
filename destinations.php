<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<!-- Destinations Page -->
<section id="map" class="position-relative vh-100 pb-5 px-5 py-3">

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
                <button class="btn btn-outline-success rounded-pill" data-filter="heritage">Heritage</button>
                <button class="btn btn-outline-success rounded-pill" data-filter="landmark">Landmarks</button>
                <button class="btn btn-outline-success rounded-pill" data-filter="museum">Museums</button>
                <button class="btn btn-outline-success rounded-pill" data-filter="religious">Religious Sites</button>
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

    <div class="row g-4" id="destinationGrid">
        <!-- Calle Crisologo -->
        <div class="col-12 col-sm-6 col-xl-3 destination-item"
            data-category="landmark" data-name="calle crisologo">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="assets/images/calle-crisologo.jpg"
                    class="card-img-top object-fit-cover"
                    height="190" alt="Calle Crisologo">

                <div class="card-body">
                    <span class="badge bg-primary-subtle text-primary mb-2">Landmark</span>
                    <h5 class="fw-bold">Calle Crisologo</h5>
                    <p class="text-muted small">
                        A historic street known for its colonial architecture
                        and vibrant local culture.
                    </p>

                    <a href="maps.php?destination=calle-crisologo"
                        class="btn btn-outline-success w-100 rounded-pill mt-2">
                        <i class="bi bi-map me-2"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

        <!-- Plaza Salcedo -->
        <div class="col-12 col-sm-6 col-xl-3 destination-item"
            data-category="landmark" data-name="plaza salcedo">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="assets/images/plaza-salcedo.jpg"
                    class="card-img-top object-fit-cover"
                    height="190" alt="Plaza Salcedo">

                <div class="card-body">
                    <span class="badge bg-primary-subtle text-primary mb-2">Landmark</span>
                    <h5 class="fw-bold">Plaza Salcedo</h5>
                    <p class="text-muted small">
                        A historic public square known for its dancing fountain
                        and surrounding heritage landmarks.
                    </p>

                    <a href="maps.php?destination=plaza-salcedo"
                        class="btn btn-outline-success w-100 rounded-pill mt-2">
                        <i class="bi bi-map me-2"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

        <!-- Bantay Bell Tower -->
        <div class="col-12 col-sm-6 col-xl-3 destination-item"
            data-category="landmark" data-name="bantay bell tower">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="assets/images/bantay-bell-tower.jpg"
                    class="card-img-top object-fit-cover"
                    height="190" alt="Bantay Bell Tower">

                <div class="card-body">
                    <span class="badge bg-primary-subtle text-primary mb-2">Landmark</span>
                    <h5 class="fw-bold">Bantay Bell Tower</h5>
                    <p class="text-muted small">
                        A historic bell tower offering a glimpse into the
                        religious and cultural heritage of the area.
                    </p>

                    <a href="maps.php?destination=bantay-bell-tower"
                        class="btn btn-outline-success w-100 rounded-pill mt-2">
                        <i class="bi bi-map me-2"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

        <!-- Vigan Cathedral -->
        <div class="col-12 col-sm-6 col-xl-3 destination-item"
            data-category="religious" data-name="vigan cathedral">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="assets/images/vigan-cathedral.jpg"
                    class="card-img-top object-fit-cover"
                    height="190" alt="Vigan Cathedral">

                <div class="card-body">
                    <span class="badge bg-danger-subtle text-danger mb-2">Religious Site</span>
                    <h5 class="fw-bold">Vigan Cathedral</h5>
                    <p class="text-muted small">
                        A historic church showcasing religious architecture
                        and the spiritual heritage of Vigan.
                    </p>

                    <a href="maps.php?destination=vigan-cathedral"
                        class="btn btn-outline-success w-100 rounded-pill mt-2">
                        <i class="bi bi-map me-2"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

        <!-- Syquia Mansion -->
        <div class="col-12 col-sm-6 col-xl-3 destination-item"
            data-category="museum" data-name="syquia mansion">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                <img src="assets/images/syquia-mansion.jpg"
                    class="card-img-top object-fit-cover"
                    height="190" alt="Syquia Mansion">

                <div class="card-body">
                    <span class="badge bg-warning-subtle text-warning-emphasis mb-2">Museum</span>
                    <h5 class="fw-bold">Syquia Mansion</h5>
                    <p class="text-muted small">
                        Discover historical artifacts, antique furniture,
                        and the lifestyle of a prominent Vigan family.
                    </p>

                    <a href="maps.php?destination=syquia-mansion"
                        class="btn btn-outline-success w-100 rounded-pill mt-2">
                        <i class="bi bi-map me-2"></i>View on Map
                    </a>
                </div>
            </div>
        </div>

    </div>

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

