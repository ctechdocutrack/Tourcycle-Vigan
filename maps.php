<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="node_modules/leaflet/dist/leaflet.css">

<!-- Main Map Section -->
<section id="map" class="position-relative vh-100 pb-5">

    <style>
    @media (max-width: 767.98px) {
        #mapSearchContainer {
            left: 50%;
            right: auto !important;
            transform: translateX(-50%);
            width: calc(100% - 2rem) !important;
            max-width: 400px;
        }
    }
    @media (max-width: 767.98px) {
        #mapDestinationPanel {
            left: 50%;
            right: auto !important;
            transform: translateX(-50%);
            width: calc(100% - 2rem) !important;
            max-width: 400px;
            max-height: calc(100% - 5rem) !important;
        }
    }

    /* Attraction Markers */
    .attraction-marker {
        background: transparent;
        border: none;
    }

    .attraction-marker-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        width: max-content;
        transform: translate(-50%, -50%);
        cursor: pointer;
    }

    .attraction-icon {
        width: 25px;
        height: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #198754;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        font-size: 12px;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.35);
    }

    .attraction-label {
        margin-top: 3px;
        padding: 2px 5px;
        background: rgba(255, 255, 255, 0.95);
        color: #333;
        font-size: 10px;
        font-weight: 600;
        line-height: 1.3;
        white-space: nowrap;
        border-radius: 4px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }
    /* Live Navigation User Marker */
    .navigation-user-marker {
        background: transparent;
        border: none;
    }

    .navigation-user-icon {
        position: relative;
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .navigation-direction-cone {
        position: absolute;
        width: 65px;
        height: 75px;
        left: 7.5px;
        top: -17px;
        background: rgba(13, 110, 253, 0.22);
        clip-path: polygon(50% 0%, 100% 100%, 50% 82%, 0% 100%);
        transform-origin: 50% 85%;
        pointer-events: none;
    }

    .navigation-arrow {
        position: relative;
        z-index: 2;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: white;
        color: #0d6efd;
        border: 3px solid #0d6efd;
        border-radius: 50%;
        font-size: 19px;
        box-shadow: 0 0 12px rgba(13, 110, 253, 0.65);
    }

    .navigation-arrow i {
        display: block;
    }

    .navigation-user-icon.active {
        animation: navigationPulse 1.8s infinite;
    }

    @keyframes navigationPulse {
        0%, 100% {
            filter: drop-shadow(0 0 3px rgba(13, 110, 253, 0.5));
        }
        50% {
            filter: drop-shadow(0 0 10px rgba(13, 110, 253, 1));
        }
    }
    </style>
    <!-- Search Bar -->
    <div id="mapSearchContainer"
        class="position-absolute top-0 end-0 m-3 w-100"
        style="max-width: 400px; z-index: 1000;">

        <div class="input-group bg-white rounded-top-4 p-1 shadow overflow-hidden">

            <span class="input-group-text bg-white border-0 ps-3">
                <i class="bi bi-search text-secondary"></i>
            </span>

            <input type="text" id="mapSearch"
                class="form-control border-0 shadow-none"
                placeholder="Search places in Vigan...">

            <button class="btn border-0 text-secondary"
                type="button" id="clearMapSearch">
                <i class="bi bi-x-lg"></i>
            </button>

        </div>

        <div id="mapSearchResults"
            class="bg-white shadow rounded-3 mt-2 d-none overflow-hidden">
        </div>

    </div>

    <!-- Destination Information Panel -->
    <div id="mapDestinationPanel"
    class="position-absolute top-0 end-0 bg-white shadow rounded-4 overflow-auto d-none m-3"
    style="max-width: 400px; max-height: calc(100% - 2rem); z-index: 999;">

        <!-- Destination Image -->
        <div class="position-relative">

            <img id="mapDestinationImage"
                src=""
                alt="Destination"
                class="w-100 object-fit-cover"
                height="200">

            <button type="button" id="closeMapPanel"
                class="btn btn-light rounded-circle shadow position-absolute top-0 end-0 m-3">
                <!-- <i class="bi bi-x-lg"></i> -->
            </button>

        </div>

        <div class="p-3">

            <span id="mapDestinationCategory"
                class="badge bg-success mb-2"></span>

            <h5 id="mapDestinationName" class="fw-bold mb-2"></h5>

            <p id="mapDestinationDescription"
                class="text-muted small"></p>

            <!-- Audio Tour Guide -->
            <div class="card bg-light border-0 mt-4">

                <div class="card-body">

                    <h5 class="fw-bold">
                        <i class="bi bi-headphones text-success me-2"></i>
                        Audio Tour Guide
                    </h5>

                    <p class="text-muted small">
                        Listen to the description and historical background of this destination.
                    </p>

                    <div class="mb-3">

                        <label for="audioLanguage"
                            class="form-label fw-semibold">
                            Narration Language
                        </label>

                        <select id="audioLanguage" class="form-select">
                            <option value="en-US">🇬🇧 English</option>
                            <option value="fil-PH">🇵🇭 Tagalog</option>
                            <option value="zh-CN">🇨🇳 Chinese (Mandarin)</option>
                            <option value="fr-FR">🇫🇷 French</option>
                            <option value="ja-JP">🇯🇵 Japanese</option>
                            <option value="ko-KR">🇰🇷 Korean</option>
                            <option value="es-ES">🇪🇸 Spanish</option>
                        </select>

                    </div>

                    <div class="d-flex flex-wrap gap-2">

                        <button type="button" id="playAudio"
                            class="btn btn-success">
                            <i class="bi bi-play-fill me-1"></i>
                            Play Audio
                        </button>

                        <button type="button" id="stopAudio"
                            class="btn btn-outline-danger">
                            <i class="bi bi-stop-fill me-1"></i>
                            Stop
                        </button>

                    </div>

                    <p id="audioStatus"
                        class="small text-muted mt-3 mb-0">
                        Audio guide is ready.
                    </p>

                </div>
            </div>

            <!-- Route Guidance -->
            <div class="card bg-light border-0 mt-3">
                <div class="card-body">
                    <h5 class="fw-bold">
                        <i class="bi bi-signpost-2 text-success me-2"></i>
                        Route Guidance
                    </h5>

                    <p class="text-muted small">
                        Find the route from your current location to this destination.
                    </p>

                    <button type="button" id="showRoute" class="btn btn-primary w-100">
                        <i class="bi bi-map me-2"></i>
                        Show Route
                    </button>

                    <button type="button" id="startNavigation" class="btn btn-success w-100 mt-2 d-none">
                        <i class="bi bi-navigation me-2"></i>
                        Start Navigation
                    </button>

                    <button type="button" id="endNavigation" class="btn btn-outline-danger w-100 mt-2 d-none">
                        <i class="bi bi-x-circle me-2"></i>
                        End Navigation
                    </button>

                    <div id="navigationStatus" class="alert alert-success small mt-3 mb-0 d-none">
                        Navigation is ready.
                    </div>

                    <div id="routeStatus" class="alert alert-light small mt-3 mb-0">
                        Select a destination and detect your location to get directions.
                    </div>
                </div>
            </div>

            <!-- Coordinates -->
            <div class="d-flex align-items-center gap-2 mt-4">
                <i class="bi bi-geo-alt-fill text-success"></i>

                <span id="destinationCoordinates"
                    class="small text-muted"></span>
            </div>

        </div>
    </div>

    <!-- Main Map -->
    <div id="touristMap" class="position-absolute top-0 start-0 w-100 h-100"></div>

    <!-- Map Controls -->
    <div class="position-absolute top-0 start-0 m-3 d-none d-md-block"
        style="z-index: 500;">

        <button type="button" id="locateMeMap"
            class="btn btn-success shadow rounded-pill px-3">
            <i class="bi bi-crosshair me-2"></i>
            My Location
        </button>

    </div>

</section>

<!-- Leaflet JS -->
<script src="node_modules/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-rotate@0.6.0/dist/leaflet-rotate.js"></script>

<!-- Map JavaScript -->
<script src="assets/js/maps.js"></script>

<!-- Sidebar JavaScript -->
<script src="assets/js/sidebar.js"></script>

<?php include 'assets/includes/footer.php'; ?>
