
<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<section id="map" class="position-relative min-vh-100 px-4 px-lg-5 py-4">

    <div class="mb-4">
        <span class="text-success fw-semibold text-uppercase small">Vigan City</span>
        <h2 class="fw-bold mt-1">Weather Information</h2>
        <p class="text-muted mb-0">Check the latest weather conditions before exploring Vigan.</p>
    </div>

    <!-- Current Weather -->
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <div>
                    <h5 class="fw-bold mb-1">Current Weather</h5>
                    <p class="text-muted small mb-0"><i class="bi bi-geo-alt-fill text-success me-1"></i>Vigan City, Ilocos Sur</p>
                </div>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2" id="weatherUpdated">Loading...</span>
            </div>

            <div class="row align-items-center g-4">
                <div class="col-12 col-md-6">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cloud-sun display-1 text-success" id="weatherIcon"></i>
                        <div>
                            <h1 class="display-3 fw-bold mb-0" id="temperature">--°C</h1>
                            <p class="text-muted mb-0" id="weatherDescription">Loading weather...</p>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 h-100">
                                <i class="bi bi-droplet-half text-primary fs-4"></i>
                                <p class="text-muted small mb-1 mt-2">Humidity</p>
                                <h5 class="fw-bold mb-0" id="humidity">--%</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 h-100">
                                <i class="bi bi-wind text-success fs-4"></i>
                                <p class="text-muted small mb-1 mt-2">Wind Speed</p>
                                <h5 class="fw-bold mb-0" id="windSpeed">-- km/h</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 h-100">
                                <i class="bi bi-thermometer-half text-danger fs-4"></i>
                                <p class="text-muted small mb-1 mt-2">Feels Like</p>
                                <h5 class="fw-bold mb-0" id="feelsLike">--°C</h5>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light rounded-4 p-3 h-100">
                                <i class="bi bi-cloud-rain text-info fs-4"></i>
                                <p class="text-muted small mb-1 mt-2">Rain Chance</p>
                                <h5 class="fw-bold mb-0" id="rainChance">--%</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="text-muted small mt-4 mb-0" id="weatherError"></p>
        </div>
    </div>

    <!-- Forecast -->
    <div class="mb-3">
        <h4 class="fw-bold mb-1">5-Day Forecast</h4>
        <p class="text-muted small mb-0">Weather outlook for the coming days.</p>
    </div>

    <div class="row g-3" id="forecastContainer">
        <div class="col-12 text-muted">Loading forecast...</div>
    </div>

    <!-- Weather Reminder -->
    <div class="alert alert-success border-0 rounded-4 mt-4 mb-0">
        <div class="d-flex gap-3">
            <i class="bi bi-info-circle-fill fs-4"></i>
            <div>
                <h6 class="fw-bold mb-1">Travel Reminder</h6>
                <p class="small mb-0">Check weather conditions before starting your tour. Bring an umbrella or raincoat when rain is expected, and always follow official weather advisories.</p>
            </div>
        </div>
    </div>

</section>

<script src="assets/js/weather.js"></script>
<script src="assets/js/sidebar.js"></script>

<?php include 'assets/includes/footer.php'; ?>
