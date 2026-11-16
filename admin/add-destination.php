<?php 
include 'includes/admin-auth.php';
include 'includes/admin-only.php';
include 'includes/admin-header.php'; 
?>

<?php include 'includes/flash-alert.php'; ?>
<link rel="stylesheet" href="../node_modules/leaflet/dist/leaflet.css">

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/admin-sidebar.php'; ?>

        <!-- Main Content -->
        <main class="col-md-10 col-lg-10 px-3 px-md-4 py-4">

            <!-- Page Heading -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                <div>
                    <h3 class="fw-bold mb-1">Add New Destination</h3>
                    <p class="text-muted mb-0">Add a new tourist destination to Tourcycle Vigan.</p>
                </div>
            </div>

            <form id="destinationForm" action="controller/addDestinationController.php" method="POST" enctype="multipart/form-data">

                <div class="row g-4">

                    <!-- Left Column: Destination Details -->
                    <div class="col-12 col-xl-7">

                        <!-- Basic Information -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 p-3">
                                <h5 class="fw-bold mb-1">Basic Information</h5>
                                <small class="text-muted">Enter the destination's general information.</small>
                            </div>

                            <div class="card-body p-3 p-md-4">
                                <div class="row g-3 mb-3">
                                    <div class="col-12 col-md-6">
                                        <label for="destinationName" class="form-label fw-semibold">Destination Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="destinationName" name="destination_name" placeholder="e.g. Calle Crisologo" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="destinationCategory" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" id="destinationCategory" name="category" required>
                                            <option value="" selected disabled>Select a category</option>
                                            <option value="Historical Landmark">Historical Landmark</option>
                                            <option value="Church & Religious Site">Church & Religious Site</option>
                                            <option value="Museum">Museum</option>
                                            <option value="Cultural Heritage">Cultural Heritage</option>
                                            <option value="Nature & Wildlife">Nature & Wildlife</option>
                                            <option value="Heritage Street">Heritage Street</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- <div class="mb-3">
                                    <label for="destinationAddress" class="form-label fw-semibold">Complete Address</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="destinationAddress" name="address" placeholder="This is to help locate the destination on the map.">
                                        <button type="button" class="btn btn-outline-success" id="findLocation">
                                            <i class="bi bi-geo-alt me-1"></i> Find
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter the address, then click Find to locate it on the map.</small>
                                </div> -->

                                <div class="mb-3">
                                    <label for="destinationDescription" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="destinationDescription" name="description" rows="6" placeholder="Write the Description of the Destination..." required></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Destination Image -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 p-3">
                                <h5 class="fw-bold mb-1">Destination Images</h5>
                                <small class="text-muted">Upload high-quality photos to showcase the destination.</small>
                            </div>

                            <div class="card-body p-3 p-md-4">

                                <!-- Main Image -->
                                <div class="mb-4">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <label for="destinationImage" class="form-label fw-semibold mb-0">
                                            Main Image <span class="text-danger">*</span>
                                        </label>
                                        <span class="badge bg-success-subtle text-success">Required</span>
                                    </div>

                                    <div class="border rounded-3 p-3 p-md-4 bg-light">
                                        <div class="text-center">
                                            <i class="bi bi-image fs-1 text-success"></i>
                                            <h6 class="fw-semibold mt-2 mb-1">Upload Main Image</h6>
                                            <small class="text-muted d-block mb-3">Choose a clear and attractive cover photo.</small>
                                            <input type="file" class="form-control" id="destinationImage" name="image" accept="image/*" required>
                                            <small class="text-muted d-block mt-2">JPG, JPEG, PNG or WEBP</small>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-none" id="imagePreviewContainer">
                                        <p class="small fw-semibold mb-2">Main Image Preview</p>
                                        <img id="imagePreview" class="img-fluid rounded-3 border" alt="Destination Preview">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Additional Images -->
                                <div>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div>
                                            <label class="form-label fw-semibold mb-1">Additional Images</label>
                                            <small class="text-muted d-block">Add more photos to highlight the destination.</small>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="addImageButton">
                                            <i class="bi bi-plus-lg me-1"></i> Add Images
                                        </button>
                                    </div>

                                    <div id="additionalImagesContainer"></div>

                                    <div class="border rounded-3 bg-light p-3 mt-3 text-center">
                                        <i class="bi bi-images text-success fs-3"></i>
                                        <p class="small text-muted mb-0 mt-2">
                                            You can upload multiple additional photos.
                                        </p>
                                        <small class="text-muted">Supported formats: JPG, JPEG, PNG and WEBP</small>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- Audio Guide Files -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 p-3">
                                <h5 class="fw-bold mb-1">Audio Guide Files</h5>
                                <small class="text-muted">Upload narration files for each supported language.</small>
                            </div>

                            <div class="card-body p-3 p-md-4">

                                <!-- English Audio -->
                                <div class="mb-4">
                                    <label for="audioEnglish" class="form-label fw-semibold">
                                        🇬🇧 English Audio <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" class="form-control" id="audioEnglish"
                                        name="audio_en" accept=".mp3,audio/mpeg" required>
                                    <small class="text-muted">Upload your English narration in MP3 format.</small>
                                </div>

                                <hr>

                                <!-- Tagalog Audio -->
                                <div class="mb-4">
                                    <label for="audioTagalog" class="form-label fw-semibold">
                                        🇵🇭 Tagalog Audio
                                    </label>
                                    <input type="file" class="form-control" id="audioTagalog"
                                        name="audio_tl" accept=".mp3,audio/mpeg" required>
                                    <small class="text-muted">Upload your Tagalog narration in MP3 format.</small>
                                </div>

                                <hr>

                                <!-- Ilokano Audio -->
                                <div>
                                    <label for="audioIlokano" class="form-label fw-semibold">
                                        🇵🇭 Ilokano Audio
                                    </label>
                                    <input type="file" class="form-control" id="audioIlokano"
                                        name="audio_ilo" accept=".mp3,audio/mpeg" required>
                                    <small class="text-muted">Upload your Ilokano narration in MP3 format</small>
                                </div>

                            </div>
                        </div>


                    </div>

                    <!-- Right Column: Map and Settings -->
                    <div class="col-12 col-xl-5">

                        <!-- Map Location -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 p-3">
                                <h5 class="fw-bold mb-1">Map Location</h5>
                                <small class="text-muted">Find and confirm the destination's coordinates.</small>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="destinationAddress" class="form-label fw-semibold">Complete Address</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="destinationAddress" name="address" placeholder="This is to help locate the destination on the map.">
                                        <button type="button" class="btn btn-outline-success" id="findLocation">
                                            <i class="bi bi-geo-alt me-1"></i> Find
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter the address, then click Find to locate it on the map.</small>
                                </div>
                                <div class="alert alert-info small mb-3">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Search the address or click directly on the map to place the marker. You can also drag the marker to adjust its position.
                                </div>

                                <div class="ratio ratio-4x3 rounded-3 overflow-hidden mb-3">
                                    <div id="destinationMap"></div>
                                </div>

                                <div class="row g-3">
                                    <div class="col-6">
                                        <label for="latitude" class="form-label fw-semibold small">Latitude</label>
                                        <input type="number" step="any" class="form-control form-control-sm" id="latitude" name="latitude" placeholder="Enter latitude">
                                    </div>
                                    <div class="col-6">
                                        <label for="longitude" class="form-label fw-semibold small">Longitude</label>
                                        <input type="number" step="any" class="form-control form-control-sm" id="longitude" name="longitude" placeholder="Enter longitude">
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <button type="button" class="btn btn-outline-secondary btn-sm w-100" id="resetMap">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i> Reset Map
                                    </button>
                                </div>

                            </div>
                        </div>

                        <!-- Destination Status -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white border-0 p-3">
                                <h5 class="fw-bold mb-1">Destination Settings</h5>
                                <small class="text-muted">Configure destination visibility.</small>
                            </div>

                            <div class="card-body p-3">
                                <label for="destinationStatus" class="form-label fw-semibold">Status</label>
                                <select class="form-select" id="destinationStatus" name="status">
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                                <small class="text-muted">Active destinations can be displayed on the public map.</small>
                            </div>
                        </div>

                    </div>

                </div>

                <!-- Form Actions -->
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-3 d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <small class="text-muted"><span class="text-danger">*</span> Required fields</small>

                        <div class="d-flex gap-2">
                            <a href="destinations.php" class="btn btn-light border">Cancel</a>
                            <button type="submit" name="save_destination" class="btn btn-success">
                                <i class="bi bi-check-lg me-1"></i> Save Destination
                            </button>
                        </div>
                    </div>
                </div>

            </form>

        </main>

    </div>
</div>

<script src="../node_modules/leaflet/dist/leaflet.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    // Initialize Map
    const defaultLocation = [17.5747, 120.3869];

    const map = L.map("destinationMap").setView(defaultLocation, 15);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
    }).addTo(map);

    let destinationMarker = null;

    // Update Coordinates
    function updateCoordinates(latitude, longitude) {

        document.getElementById("latitude").value = latitude.toFixed(7);
        document.getElementById("longitude").value = longitude.toFixed(7);

    }

    // Set Marker
    function setDestinationMarker(latitude, longitude) {

        const location = [latitude, longitude];

        if (destinationMarker) {
            destinationMarker.setLatLng(location);
        } else {
            destinationMarker = L.marker(location, {
                draggable: true
            }).addTo(map);

            destinationMarker.on("dragend", function () {
                const position = destinationMarker.getLatLng();
                updateCoordinates(position.lat, position.lng);
            });
        }

        updateCoordinates(latitude, longitude);
        map.setView(location, 17);

    }

    // Click Map to Set Location
    map.on("click", function (event) {
        setDestinationMarker(event.latlng.lat, event.latlng.lng);
    });

    // Manually Enter Coordinates
    async function updateMapFromInputs() {

        const latitude = parseFloat(document.getElementById("latitude").value);
        const longitude = parseFloat(document.getElementById("longitude").value);

        if (
            Number.isNaN(latitude) ||
            Number.isNaN(longitude) ||
            latitude < -90 ||
            latitude > 90 ||
            longitude < -180 ||
            longitude > 180
        ) {
            return;
        }

        setDestinationMarker(latitude, longitude);

        // Automatically Find Address
        const addressInput = document.getElementById("destinationAddress");

        try {

            const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${latitude}&lon=${longitude}`;

            const response = await fetch(url);

            if (!response.ok) {
                throw new Error("Address lookup failed.");
            }

            const result = await response.json();

            if (result.display_name) {
                addressInput.value = result.display_name;
            }

        } catch (error) {
            console.error("Reverse geocoding error:", error);
        }

    }

    document.getElementById("latitude").addEventListener("change", updateMapFromInputs);
    document.getElementById("longitude").addEventListener("change", updateMapFromInputs);

    // Find Location using OpenStreetMap Nominatim
    document.getElementById("findLocation").addEventListener("click", async function () {

        const address = document.getElementById("destinationAddress").value.trim();
        const button = this;

        if (!address) {
            alert("Please enter the destination address first.");
            return;
        }

        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Searching...';

        try {

            const searchAddress = address + ", Ilocos Sur, Philippines";
            const url = "https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=" + encodeURIComponent(searchAddress);

            const response = await fetch(url);
            const results = await response.json();

            if (results.length > 0) {

                const latitude = parseFloat(results[0].lat);
                const longitude = parseFloat(results[0].lon);

                setDestinationMarker(latitude, longitude);

            } else {

                alert("Location not found. Please try a more complete address or select the location directly on the map.");

            }

        } catch (error) {

            alert("Unable to search for the location. Please try again or select it directly on the map.");

        } finally {

            button.disabled = false;
            button.innerHTML = '<i class="bi bi-geo-alt me-1"></i> Find';

        }

    });

    // Reset Map
    document.getElementById("resetMap").addEventListener("click", function () {

        if (destinationMarker) {
            map.removeLayer(destinationMarker);
            destinationMarker = null;
        }

        document.getElementById("latitude").value = "";
        document.getElementById("longitude").value = "";

        map.setView(defaultLocation, 15);

    });

    // Image Preview
    document.getElementById("destinationImage").addEventListener("change", function () {

        const file = this.files[0];
        const preview = document.getElementById("imagePreview");
        const previewContainer = document.getElementById("imagePreviewContainer");

        if (file) {

            if (!file.type.startsWith("image/")) {
                alert("Please select a valid image file.");
                this.value = "";
                previewContainer.classList.add("d-none");
                return;
            }

            preview.src = URL.createObjectURL(file);
            previewContainer.classList.remove("d-none");

        } else {

            previewContainer.classList.add("d-none");

        }

    });

    // Frontend Form Validation
    document.getElementById("destinationForm").addEventListener("submit", function (event) {

        const latitude = document.getElementById("latitude").value;
        const longitude = document.getElementById("longitude").value;
        const submitButton = this.querySelector('[type="submit"]');

        if (!latitude || !longitude) {
            event.preventDefault();
            AppAlert.warning("Location Required!", "Please select the destination's location on the map.");
            return;
        }

        if (submitButton.disabled) {
            event.preventDefault();
            return;
        }

        submitButton.disabled = true;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving...';

    });

});

const addImageButton = document.getElementById("addImageButton");
const additionalImagesContainer = document.getElementById("additionalImagesContainer");

addImageButton.addEventListener("click", function () {
    const imageInputGroup = document.createElement("div");
    imageInputGroup.className = "mb-3";

    imageInputGroup.innerHTML = `
        <div class="input-group">
            <input type="file" class="form-control" name="additional_images[]" accept="image/*">
            <button type="button" class="btn btn-outline-danger removeImageButton">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    `;

    additionalImagesContainer.appendChild(imageInputGroup);

    imageInputGroup.querySelector(".removeImageButton").addEventListener("click", function () {
        imageInputGroup.remove();
    });
});

</script>

<?php include 'includes/admin-footer.php'; ?>
