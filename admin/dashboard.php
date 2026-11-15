<?php 
include 'includes/admin-auth.php';
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
                    <h3 class="fw-bold mb-1">Dashboard</h3>
                    <p class="text-muted mb-0">
                        Welcome to your Tourcycle Vigan administration panel.
                    </p>
                </div>

                <a href="add-destination.php" class="btn btn-success">
                    <i class="bi bi-plus-lg me-2"></i>
                    Add Destination
                </a>

            </div>

            <!-- Statistics -->
            <div class="row g-3 mb-4">

                <!-- Total Destinations -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-semibold">
                                    Total Destinations
                                </span>
                                <span class="bg-success-subtle text-success rounded-3 p-2">
                                    <i class="bi bi-geo-alt fs-5"></i>
                                </span>
                            </div>

                            <h2 class="fw-bold mb-1">05</h2>
                            <small class="text-muted">Registered destinations</small>
                        </div>
                    </div>
                </div>

                <!-- Categories -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-semibold">
                                    Categories
                                </span>
                                <span class="bg-primary-subtle text-primary rounded-3 p-2">
                                    <i class="bi bi-tags fs-5"></i>
                                </span>
                            </div>

                            <h2 class="fw-bold mb-1">03</h2>
                            <small class="text-muted">Available categories</small>
                        </div>
                    </div>
                </div>

                <!-- Available Languages -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-semibold">Available Languages</span>
                                <span class="bg-info-subtle text-info rounded-3 p-2">
                                    <i class="bi bi-translate fs-5"></i>
                                </span>
                            </div>

                            <h2 class="fw-bold mb-1">03</h2>
                            <small class="text-muted">Supported languages</small>
                        </div>
                    </div>
                </div>

                <!-- Audio Guides -->
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted small fw-semibold">Audio Guides</span>
                                <span class="bg-warning-subtle text-warning rounded-3 p-2">
                                    <i class="bi bi-headphones fs-5"></i>
                                </span>
                            </div>

                            <h2 class="fw-bold mb-1">05</h2>
                            <small class="text-muted">Available audio narrations</small>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Destination Overview -->
            <div class="row g-3">

                <!-- Recent Destinations -->
                <div class="col-12 col-lg-8">
                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-0 p-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Recent Destinations</h5>

                            <a href="destinations.php"
                                class="text-success text-decoration-none small fw-semibold">
                                View All
                            </a>
                        </div>

                        <div class="card-body p-0">
                            <div class="table-responsive">

                                <table class="table table-hover align-middle mb-0">

                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-3">Destination</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="ps-3 fw-semibold">Calle Crisologo</td>
                                            <td>Historical Site</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>

                                        <tr>
                                            <td class="ps-3 fw-semibold">Plaza Salcedo</td>
                                            <td>Historical Landmark</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>

                                        <tr>
                                            <td class="ps-3 fw-semibold">Bantay Bell Tower</td>
                                            <td>Historical Landmark</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>

                                        <tr>
                                            <td class="ps-3 fw-semibold">Vigan Cathedral</td>
                                            <td>Church</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>

                                        <tr>
                                            <td class="ps-3 fw-semibold">Syquia Mansion</td>
                                            <td>Historical Landmark</td>
                                            <td><span class="badge bg-success">Active</span></td>
                                        </tr>
                                    </tbody>

                                </table>

                            </div>
                        </div>

                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="col-12 col-lg-4">
                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-header bg-white border-0 p-3">
                            <h5 class="fw-bold mb-0">Quick Actions</h5>
                        </div>

                        <div class="card-body d-flex flex-column gap-3">

                            <a href="add-destination.php"
                                class="btn btn-outline-success text-start p-3">
                                <i class="bi bi-plus-circle me-2"></i>
                                Add New Destination
                            </a>

                            <a href="destinations.php"
                                class="btn btn-outline-success text-start p-3">
                                <i class="bi bi-geo-alt me-2"></i>
                                Manage Destinations
                            </a>

                            <a href="categories.php"
                                class="btn btn-outline-success text-start p-3">
                                <i class="bi bi-tags me-2"></i>
                                Manage Categories
                            </a>

                            <a href="../index.php"
                                class="btn btn-outline-secondary text-start p-3">
                                <i class="bi bi-globe me-2"></i>
                                Visit Public Website
                            </a>

                        </div>

                    </div>
                </div>

            </div>

        </main>

    </div>
</div>

<?php include 'includes/admin-footer.php'; ?>
