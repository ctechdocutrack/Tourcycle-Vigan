
<!-- Desktop Sidebar -->
<aside id="mainSidebar"
    class="position-fixed top-0 start-0 vh-100 bg-white shadow d-none d-lg-flex flex-column"
    style="width: 260px; z-index: 1050; transition: width 0.3s;">

    <!-- Header -->
    <div class="d-flex align-items-center p-3 border-bottom">
        <button id="sidebarToggle" class="btn btn-light rounded-circle me-3">
            <i class="bi bi-list fs-4"></i>
        </button>

        <a href="index.html" id="sidebarBrand"
            class="text-success fw-bold text-decoration-none fs-5">
            TourCycle Vigan
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-grow-1 overflow-auto p-2">

        <p class="sidebar-text text-muted small fw-bold px-3 mt-3">MENU</p>

        <a href="maps.php" class="sidebar-link nav-link active bg-success bg-opacity-10 text-success rounded-3 p-3 mb-2">
            <i class="bi bi-map fs-5"></i>
            <span class="sidebar-text ms-3">Explore Map</span>
        </a>

        <a href="destinations.php" class="sidebar-link nav-link text-dark rounded-3 p-3 mb-2">
            <i class="bi bi-geo-alt fs-5"></i>
            <span class="sidebar-text ms-3">Destinations</span>
        </a>

        <!-- <a href="#nearby" class="sidebar-link nav-link text-dark rounded-3 p-3 mb-2">
            <i class="bi bi-compass fs-5"></i>
            <span class="sidebar-text ms-3">Nearby Attractions</span>
        </a> -->

        <hr>

        <p class="sidebar-text text-muted small fw-bold px-3">INFORMATION</p>

        <!-- <a href="weather-information.php" class="sidebar-link nav-link text-dark rounded-3 p-3 mb-2">
            <i class="bi bi-cloud-sun fs-5"></i>
            <span class="sidebar-text ms-3">Weather</span>
        </a> -->

        <a href="emergency-contacts.php" class="sidebar-link nav-link text-dark rounded-3 p-3 mb-2">
            <i class="bi bi-telephone fs-5"></i>
            <span class="sidebar-text ms-3">Emergency Contacts</span>
        </a>

        <a href="about.php" class="sidebar-link nav-link text-dark rounded-3 p-3 mb-2">
            <i class="bi bi-info-circle fs-5"></i>
            <span class="sidebar-text ms-3">About</span>
        </a>

    </div>

    <!-- Footer -->
    <div class="border-top p-2">
        <button id="locateMe" class="btn btn-success w-100 p-3">
            <i class="bi bi-crosshair fs-5"></i>
            <span class="sidebar-text ms-2">Find My Location</span>
        </button>
    </div>

</aside>


<!-- Mobile Bottom Navigation -->
<nav id="mobileBottomNav"
    class="navbar fixed-bottom bg-white border-top shadow-lg d-lg-none">

    <div class="container-fluid d-flex justify-content-around align-items-center px-1 py-2">

        <a href="maps.php"
            class="nav-link text-success d-flex flex-column align-items-center gap-1">
            <i class="bi bi-map fs-4"></i>
            <small>Explore</small>
        </a>

        <a href="destinations.php"
            class="nav-link text-secondary d-flex flex-column align-items-center gap-1">
            <i class="bi bi-geo-alt fs-4"></i>
            <small>Places</small>
        </a>

        <!-- <a href="#nearby"
            class="nav-link text-secondary d-flex flex-column align-items-center gap-1">
            <i class="bi bi-compass fs-4"></i>
            <small>Nearby</small>
        </a> -->

        <!-- <a href="weather-information.php"
            class="nav-link text-secondary d-flex flex-column align-items-center gap-1">
            <i class="bi bi-cloud-sun fs-4"></i>
            <small>Weather</small>
        </a> -->

        <a href="emergency-contacts.php"
            class="nav-link text-secondary d-flex flex-column align-items-center gap-1">
            <i class="bi bi-telephone fs-4"></i>
            <small>Emergency</small>
        </a>

        <a href="about.php"
            class="nav-link text-secondary d-flex flex-column align-items-center gap-1">
            <i class="bi bi-info-circle fs-4"></i>
            <small>About</small>
        </a>

        <!-- <button type="button" id="mobileLocateMe"
            class="btn btn-link text-secondary text-decoration-none d-flex flex-column align-items-center gap-1 p-0">
            <i class="bi bi-crosshair fs-4"></i>
            <small>Location</small>
        </button> -->

    </div>
</nav>
