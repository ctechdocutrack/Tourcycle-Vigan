<?php
session_start();

$destinationId = $_GET['id'] ?? '';
$destination = null;

foreach ($_SESSION['destinations'] ?? [] as $row) {

    if ((string) $row['destinations_id'] === (string) $destinationId) {
        $destination = $row;
        break;
    }

}

if (!$destination) {

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Destination Not Found!',
        'message' => 'The selected destination could not be found.'
    ];

    header('Location: destinations.php');
    exit;
}

$additionalImages = $_SESSION['additional_images'][$destinationId] ?? [];
?>

<?php 
include 'includes/admin-auth.php';
include 'includes/admin-header.php'; 
?>

<?php include 'includes/admin-header.php'; ?>
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

            <form id="destinationForm" action="controller/editDestinationController.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="destination_id" value="<?php echo htmlspecialchars($destination['destinations_id']); ?>">

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
                                        <input type="text" class="form-control" id="destinationName" name="destination_name" value="<?php echo htmlspecialchars($destination['destination_name']); ?>" required>
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="destinationCategory" class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                                        <select class="form-select" id="destinationCategory" name="category" required>
                                            <option value="" disabled>Select a category</option>
                                            <option value="Historical Landmark" <?php echo $destination['category'] === 'Historical Landmark' ? 'selected' : ''; ?>>Historical Landmark</option>
                                            <option value="Church & Religious Site" <?php echo $destination['category'] === 'Church & Religious Site' ? 'selected' : ''; ?>>Church & Religious Site</option>
                                            <option value="Museum" <?php echo $destination['category'] === 'Museum' ? 'selected' : ''; ?>>Museum</option>
                                            <option value="Cultural Heritage" <?php echo $destination['category'] === 'Cultural Heritage' ? 'selected' : ''; ?>>Cultural Heritage</option>
                                            <option value="Nature & Wildlife" <?php echo $destination['category'] === 'Nature & Wildlife' ? 'selected' : ''; ?>>Nature & Wildlife</option>
                                            <option value="Heritage Street" <?php echo $destination['category'] === 'Heritage Street' ? 'selected' : ''; ?>>Heritage Street</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="destinationDescription" class="form-label fw-semibold">Description <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="destinationDescription" name="description" rows="6" placeholder="Write the Description of the Destination..." required><?php echo htmlspecialchars($destination['description']); ?></textarea>
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
                                            Main Image
                                        </label>
                                        <span class="badge bg-secondary-subtle text-secondary">Optional</span>
                                    </div>

                                    <div class="border rounded-3 p-3 p-md-4 bg-light">
                                        <div class="text-center">
                                            <img src="<?php echo htmlspecialchars($destination['image']); ?>"
                                                class="img-fluid rounded-3 border mb-3"
                                                style="max-height: 220px;"
                                                alt="Current Destination Image">

                                            <h6 class="fw-semibold mb-1">Current Main Image</h6>
                                            <small class="text-muted d-block mb-3">Choose a new image only if you want to replace it.</small>

                                            <input type="file" class="form-control" id="destinationImage" name="image" accept="image/*">

                                            <small class="text-muted d-block mt-2">JPG, JPEG, PNG or WEBP</small>
                                        </div>
                                    </div>

                                    <div class="mt-3 d-none" id="imagePreviewContainer">
                                        <p class="small fw-semibold mb-2">New Image Preview</p>
                                        <img id="imagePreview" class="img-fluid rounded-3 border" alt="Destination Preview">
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Additional Images -->
                                <div>
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-2">
                                        <div>
                                            <label class="form-label fw-semibold mb-1">Additional Images</label>
                                            <small class="text-muted d-block">Manage existing photos or add new ones.</small>
                                        </div>
                                        <button type="button" class="btn btn-outline-success btn-sm" id="addImageButton">
                                            <i class="bi bi-plus-lg me-1"></i> Add Images
                                        </button>
                                    </div>

                                    <?php if (!empty($additionalImages)): ?>
                                        <div class="row g-3 mb-3">
                                            <?php foreach ($additionalImages as $image): ?>
                                                <div class="col-6 col-md-4">
                                                    <img src="<?php echo htmlspecialchars($image); ?>"
                                                        class="img-fluid rounded-3 border w-100"
                                                        style="height: 150px; object-fit: cover;"
                                                        alt="Additional Destination Image">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted small mb-3">No additional images uploaded.</p>
                                    <?php endif; ?>

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
                                <small class="text-muted">Manage existing narration files or upload replacements.</small>
                            </div>

                            <div class="card-body p-3 p-md-4">

                                <!-- English Audio -->
                                <div class="mb-4">
                                    <label for="audioEnglish" class="form-label fw-semibold">
                                        🇬🇧 English Audio
                                    </label>

                                    <?php if (!empty($destination['audio_en'])): ?>
                                        <audio controls class="w-100 mb-3">
                                            <source src="<?php echo htmlspecialchars($destination['audio_en']); ?>" type="audio/mpeg">
                                        </audio>
                                    <?php endif; ?>

                                    <input type="file" class="form-control" id="audioEnglish"
                                        name="audio_en" accept=".mp3,audio/mpeg">

                                    <small class="text-muted">Upload a new MP3 only if you want to replace the existing audio.</small>
                                </div>

                                <hr>

                                <!-- Tagalog Audio -->
                                <div class="mb-4">
                                    <label for="audioTagalog" class="form-label fw-semibold">
                                        🇵🇭 Tagalog Audio
                                    </label>

                                    <?php if (!empty($destination['audio_tl'])): ?>
                                        <audio controls class="w-100 mb-3">
                                            <source src="<?php echo htmlspecialchars($destination['audio_tl']); ?>" type="audio/mpeg">
                                        </audio>
                                    <?php endif; ?>

                                    <input type="file" class="form-control" id="audioTagalog"
                                        name="audio_tl" accept=".mp3,audio/mpeg">

                                    <small class="text-muted">Upload a new MP3 only if you want to replace the existing audio.</small>
                                </div>

                                <hr>

                                <!-- Ilokano Audio -->
                                <div>
                                    <label for="audioIlokano" class="form-label fw-semibold">
                                        🇵🇭 Ilokano Audio
                                    </label>

                                    <?php if (!empty($destination['audio_ilo'])): ?>
                                        <audio controls class="w-100 mb-3">
                                            <source src="<?php echo htmlspecialchars($destination['audio_ilo']); ?>" type="audio/mpeg">
                                        </audio>
                                    <?php endif; ?>

                                    <input type="file" class="form-control" id="audioIlokano"
                                        name="audio_ilo" accept=".mp3,audio/mpeg">

                                    <small class="text-muted">Upload a new Ilokano MP3 only if you want to replace the existing audio.</small>
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
                                        <input type="text" class="form-control form-control-sm" id="latitude" name="latitude"
                                            placeholder="Not set" value="<?php echo htmlspecialchars($destination['latitude']); ?>" readonly>
                                    </div>

                                    <div class="col-6">
                                        <label for="longitude" class="form-label fw-semibold small">Longitude</label>
                                        <input type="text" class="form-control form-control-sm" id="longitude" name="longitude"
                                            placeholder="Not set" value="<?php echo htmlspecialchars($destination['longitude']); ?>" readonly>
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
                                    <option value="Active" <?php echo $destination['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $destination['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
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

    const initialLatitude = parseFloat(document.getElementById("latitude").value);
    const initialLongitude = parseFloat(document.getElementById("longitude").value);

    const hasExistingLocation = Number.isFinite(initialLatitude) && Number.isFinite(initialLongitude);

    const map = L.map("destinationMap").setView(
        hasExistingLocation ? [initialLatitude, initialLongitude] : defaultLocation,
        hasExistingLocation ? 17 : 15
    );

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

    // Load Existing Destination Location
    if (hasExistingLocation) {
        setDestinationMarker(initialLatitude, initialLongitude);
    }

    // Click Map to Set Location
    map.on("click", function (event) {
        setDestinationMarker(event.latlng.lat, event.latlng.lng);
    });

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

        latitudeInput.value = "";
        longitudeInput.value = "";

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

});

</script>

<?php include 'includes/admin-footer.php'; ?>
