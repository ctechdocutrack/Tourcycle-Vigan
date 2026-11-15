<!-- Desktop Sidebar -->
<aside class="col-md-2 col-lg-2 bg-white border-end min-vh-100 d-none d-md-flex flex-column p-3 sticky-top vh-100">

    <div class="d-flex align-items-center gap-2 mb-4">
        <i class="bi bi-map-fill text-success fs-2"></i>
        <div>
            <h5 class="mb-0 fw-bold">Tourcycle Vigan</h5>
            <small class="text-muted">Admin Panel</small>
        </div>
    </div>
    <div class="mb-4">
        <small class="text-muted fw-semibold">MAIN MENU</small>
    </div>

    <div class="nav nav-pills flex-column gap-2">

        <?php if ($_SESSION['admin_role'] === 'admin'): ?>
            <a href="dashboard.php" class="nav-link active">
                <i class="bi bi-grid me-2"></i>
                Dashboard
            </a>
        <?php endif; ?>

        <?php if ($_SESSION['admin_role'] === 'admin'): ?>
            <a href="destinations.php" class="nav-link text-dark">
                <i class="bi bi-geo-alt me-2"></i>
                Destinations
            </a>
        <?php endif; ?>

        <a href="access-management.php" class="nav-link text-dark">
            <i class="bi bi-shield-lock me-2"></i>
            Access Management
        </a>

        <hr>

        <small class="text-muted fw-semibold">SYSTEM</small>

        <?php if ($_SESSION['admin_role'] === 'admin'): ?>
            <a href="settings.php"
                class="nav-link text-dark">
                <i class="bi bi-gear me-2"></i>
                Settings
            </a>
        <?php endif; ?>

        <a href="admin-profile.php" class="nav-link text-dark">
            <i class="bi bi-person-circle me-2"></i>
            My Profile
        </a>

        <a href="../index.php"
            class="nav-link text-dark">
            <i class="bi bi-globe me-2"></i>
            View Website
        </a>

    </div>
    <div class="mt-auto pt-3 border-top">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-person-circle fs-3 text-success"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">
                    <?= htmlspecialchars($_SESSION['admin_username']) ?>
                </div>
                <small class="text-muted">
                    <?= htmlspecialchars(ucfirst($_SESSION['admin_role'])) ?> Account
                </small>
            </div>
            <a href="controller/logoutController.php" class="text-danger" title="Logout">
                <i class="bi bi-box-arrow-right fs-5"></i>
            </a>
        </div>
    </div>

</aside>

<!-- Mobile Navbar -->
<nav class="navbar navbar-dark bg-success d-md-none sticky-top shadow-sm">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold mb-0" href="dashboard.php">
            <i class="bi bi-map-fill me-2"></i>
            Tourcycle Vigan
        </a>

        <button class="navbar-toggler border-0" type="button"
            data-bs-toggle="offcanvas"
            data-bs-target="#adminSidebar"
            aria-controls="adminSidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<!-- Mobile Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="adminSidebar">

    <div class="offcanvas-header border-bottom">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-map-fill text-success fs-2"></i>
            <div>
                <h5 class="offcanvas-title fw-bold mb-0">Tourcycle Vigan</h5>
                <small class="text-muted">Admin Panel</small>
            </div>
        </div>

        <button type="button" class="btn-close"
            data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body d-flex flex-column">

        <small class="text-muted fw-semibold mb-3">MAIN MENU</small>

        <div class="nav nav-pills flex-column gap-2">

            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                <a href="dashboard.php" class="nav-link active">
                    <i class="bi bi-grid me-2"></i> Dashboard
                </a>
            <?php endif; ?>

            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                <a href="destinations.php" class="nav-link text-dark">
                    <i class="bi bi-geo-alt me-2"></i> Destinations
                </a>
            <?php endif; ?>

            <a href="access-management.php" class="nav-link text-dark">
                <i class="bi bi-shield-lock me-2"></i>
                Access Management
            </a>

            <hr>

            <small class="text-muted fw-semibold">SYSTEM</small>

            <?php if ($_SESSION['admin_role'] === 'admin'): ?>
                <a href="settings.php" class="nav-link text-dark">
                    <i class="bi bi-gear me-2"></i> Settings
                </a>
             <?php endif; ?>

            <a href="admin-profile.php" class="nav-link text-dark">
                <i class="bi bi-person-circle me-2"></i> My Profile
            </a>

            <a href="../index.php" class="nav-link text-dark">
                <i class="bi bi-globe me-2"></i> View Website
            </a>

        </div>

        <div class="mt-auto pt-3 border-top">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-person-circle fs-3 text-success"></i>
                <div class="flex-grow-1">
                    <div class="fw-semibold">
                        <?= htmlspecialchars($_SESSION['admin_username']) ?>
                    </div>
                    <small class="text-muted">
                        <?= htmlspecialchars(ucfirst($_SESSION['admin_role'])) ?> Account
                    </small>
                </div>
                <a href="controller/logoutController.php" class="text-danger" title="Logout">
                    <i class="bi bi-box-arrow-right fs-5"></i>
                </a>
            </div>
        </div>

    </div>
</div>