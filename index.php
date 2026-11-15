<?php
include 'config/dbconn.php';
// Identify Driver from QR Code
$driverId = null;
$driverToken = $_GET['driver'] ?? '';

if ($driverToken !== '' && preg_match('/^[a-f0-9]{64}$/i', $driverToken)) {
    $driverToken = mysqli_real_escape_string($conn, $driverToken);

    $driverQuery = mysqli_query($conn, "
        SELECT admin_id
        FROM admin
        WHERE qr_token = '$driverToken'
        AND role = 'driver'
        LIMIT 1
    ");

    if ($driverQuery && mysqli_num_rows($driverQuery) > 0) {
        $driver = mysqli_fetch_assoc($driverQuery);
        $driverId = (int) $driver['admin_id'];
    }
}

// Generate a secure device token
$deviceToken = $_COOKIE['tour_device_token'] ?? '';
$tokenHash = $deviceToken ? hash('sha256', $deviceToken) : '';

$tourSession = null;

// Check existing visitor session
if ($tokenHash) {

    $tokenHash = mysqli_real_escape_string($conn, $tokenHash);

    $result = mysqli_query($conn, "
        SELECT * FROM tour_sessions
        WHERE device_token_hash = '$tokenHash'
        LIMIT 1
    ");

    if ($result) {
        $tourSession = mysqli_fetch_assoc($result);
    }

}

// Assign existing Pending session to the scanned Driver
if (
    $tourSession &&
    $tourSession['status'] === 'Pending' &&
    $driverId !== null
) {
    mysqli_query($conn, "
        UPDATE tour_sessions
        SET admin_id = $driverId
        WHERE tour_sessions_id = " . (int) $tourSession['tour_sessions_id'] . "
    ");

    $tourSession['admin_id'] = $driverId;
}

// Create a new session if none exists or previous session has ended/expired
if (
    !$tourSession ||
    in_array($tourSession['status'], ['Ended', 'Expired'], true)
) {

    $deviceToken = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $deviceToken);

    // Generate unique Tour ID and Access Code
    $tourId = 'TV-' . strtoupper(bin2hex(random_bytes(4)));
    $accessCode = strtoupper(bin2hex(random_bytes(4)));

    // Detect device type
    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $deviceType = preg_match('/mobile|android|iphone|ipad/i', $userAgent)
        ? 'Mobile'
        : 'Desktop';

    // Detect browser
    $browser = 'Other';

    if (stripos($userAgent, 'Edg') !== false) {
        $browser = 'Microsoft Edge';
    } elseif (stripos($userAgent, 'Chrome') !== false) {
        $browser = 'Google Chrome';
    } elseif (stripos($userAgent, 'Firefox') !== false) {
        $browser = 'Mozilla Firefox';
    } elseif (stripos($userAgent, 'Safari') !== false) {
        $browser = 'Safari';
    }

    $deviceType = mysqli_real_escape_string($conn, $deviceType);
    $browser = mysqli_real_escape_string($conn, $browser);
    $tourId = mysqli_real_escape_string($conn, $tourId);
    $accessCode = mysqli_real_escape_string($conn, $accessCode);

    $driverIdValue = $driverId !== null ? (string) $driverId : 'NULL';

    $insert = mysqli_query($conn, "
        INSERT INTO tour_sessions (
            tour_id, admin_id, access_code, device_token_hash,
            device_type, browser, last_activity_at
        ) VALUES (
            '$tourId', $driverIdValue, '$accessCode', '$tokenHash',
            '$deviceType', '$browser', NOW()
        )
    ");

    if (!$insert) {
        die('Unable to create tour session: ' . mysqli_error($conn));
    }

    // Save device token in a secure browser cookie
    setcookie('tour_device_token', $deviceToken, [
        'expires' => time() + (365 * 24 * 60 * 60),
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    $result = mysqli_query($conn, "
        SELECT * FROM tour_sessions
        WHERE device_token_hash = '$tokenHash'
        LIMIT 1
    ");

    $tourSession = mysqli_fetch_assoc($result);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome | TourCycle Vigan</title>

    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.min.css">
</head>

<body>

    <!-- Welcome Screen -->
    <main id="welcomeScreen" class="position-relative vh-100 overflow-hidden bg-dark">

        <!-- Background Image -->
        <img src="assets/images/calle-crisologo.jpg"
            alt="Calle Crisologo"
            class="position-absolute top-0 start-0 w-100 h-100 object-fit-cover opacity-50">

        <!-- Dark Overlay -->
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark bg-opacity-50"></div>

        <!-- Welcome Content -->
        <div class="position-relative d-flex flex-column justify-content-center align-items-center text-center text-white h-100 p-4">

            <img src="assets/images/Tourcycle Vigan.png" alt="TourCycle Vigan Logo" class="mb-4" style="width: 120px;">

            <p class="text-uppercase fw-semibold text-warning mb-2">
                Discover • Explore • Experience
            </p>

            <h1 class="display-3 fw-bold mb-3">
                Welcome to<br>TourCycle Vigan
            </h1>

            <p class="lead mb-5">
                Discover the beauty, history, and heritage<br>
                of the Historic City of Vigan.
            </p>

            <!-- Swipe to Start -->
            <div class="bg-white rounded-pill p-2 shadow-lg w-100"
                style="max-width: 350px;">

                <div class="d-flex align-items-center gap-3 px-2">

                    <i class="bi bi-arrow-right-circle-fill text-success fs-2"></i>

                    <input type="range" id="startTourSlider"
                        class="form-range flex-grow-1 mb-0"
                        min="0" max="100" value="0">

                    <i class="bi bi-chevron-double-right text-success"></i>

                </div>

            </div>

            <small class="text-white mt-3">
                Slide to start your tour
            </small>

        </div>
    </main>

    <!-- Language Selection Screen -->
    <main id="languageScreen" class="vh-100 bg-dark text-white d-none">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center p-4">

            <h2 class="fw-bold mb-3">Choose Your Language</h2>

            <p class="text-white-50 mb-4">
                Select your preferred language for the tour.
            </p>

            <div class="d-grid gap-3 w-100" style="max-width: 350px;">

                <button type="button" class="btn btn-light btn-lg language-option" data-language="en">
                    🇺🇸 English
                </button>

                <button type="button" class="btn btn-light btn-lg language-option" data-language="tl">
                    🇵🇭 Tagalog
                </button>

                <button type="button" class="btn btn-light btn-lg language-option" data-language="ilo">
                    🇵🇭 Ilokano
                </button>

            </div>

        </div>
    </main>

        <!-- Access Pending Screen -->
    <main id="pendingScreen" class="vh-100 bg-dark text-white d-none">
        <div class="container h-100 d-flex flex-column justify-content-center align-items-center text-center p-4">

            <i class="bi bi-hourglass-split text-warning display-1 mb-4"></i>

            <h2 id="accessStatusTitle" class="fw-bold mb-3">
                Waiting for Approval
            </h2>

            <p id="accessStatusMessage" class="text-white-50 mb-4">
                Please present your Tour ID to the administrator
                and wait for your access to be activated.
            </p>

            <div class="bg-white text-dark rounded-4 p-4 w-100" style="max-width: 350px;">

                <small class="text-muted fw-semibold">YOUR TOUR ID</small>

                <h2 class="fw-bold text-primary my-3">
                    <?= htmlspecialchars($tourSession['tour_id']) ?>
                </h2>

                <small class="text-muted">
                    Show this ID to the administrator.
                </small>

            </div>

            <div class="mt-4">
                <div class="spinner-border text-warning" role="status"></div>
                <p id="accessStatusDescription" class="text-white-50 mt-2 mb-0">
                    Waiting for admin activation...
                </p>
            </div>

        </div>
    </main>


    <script>
        const startTourSlider = document.getElementById('startTourSlider');
        const welcomeScreen = document.getElementById('welcomeScreen');

        let tourStarted = false;

        startTourSlider.addEventListener('input', function () {

            if (this.value >= 95 && !tourStarted) {

                tourStarted = true;
                startTourSlider.disabled = true;

                // Detect User Location
                if (navigator.geolocation) {

                    navigator.geolocation.getCurrentPosition(
                        function (position) {

                            sessionStorage.setItem('userLatitude', position.coords.latitude);
                            sessionStorage.setItem('userLongitude', position.coords.longitude);

                            proceedToMap();

                        },
                        function (error) {

                            console.log('Location detection failed:', error.message);

                            // Continue even if location is denied
                            proceedToMap();

                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 10000,
                            maximumAge: 0
                        }
                    );

                } else {

                    proceedToMap();

                }

            }

        });

        function proceedToMap() {

            const transition = welcomeScreen.animate(
                [
                    { transform: 'translateY(0)', opacity: 1 },
                    { transform: 'translateY(-100%)', opacity: 0 }
                ],
                {
                    duration: 800,
                    easing: 'ease-in-out',
                    fill: 'forwards'
                }
            );

            transition.onfinish = function () {
                welcomeScreen.classList.add('d-none');
                document.getElementById('languageScreen').classList.remove('d-none');
            };

        }
       
        // Access Status Monitoring
        let accessCheckInterval = null;

        const pendingScreen = document.getElementById('pendingScreen');
        const accessStatusTitle = document.getElementById('accessStatusTitle');
        const accessStatusMessage = document.getElementById('accessStatusMessage');
        const accessStatusDescription = document.getElementById('accessStatusDescription');
        const pendingSpinner = pendingScreen.querySelector('.spinner-border');

        function showAccessScreen(status) {

            welcomeScreen.classList.add('d-none');
            document.getElementById('languageScreen').classList.add('d-none');
            pendingScreen.classList.remove('d-none');

            pendingSpinner.classList.remove('d-none');

            if (status === 'Pending') {
                accessStatusTitle.textContent = 'Waiting for Approval';
                accessStatusMessage.textContent = 'Please present your Tour ID to the administrator and wait for your access to be activated.';
                accessStatusDescription.textContent = 'Waiting for admin activation...';

            } else if (status === 'Inactive') {
                accessStatusTitle.textContent = 'Access Temporarily Deactivated';
                accessStatusMessage.textContent = 'Your access has been temporarily deactivated by the administrator.';
                accessStatusDescription.textContent = 'Waiting for access reactivation...';

            } else if (status === 'Expired' || status === 'Ended') {
                accessStatusTitle.textContent = 'Access No Longer Available';
                accessStatusMessage.textContent = 'Your tour access is no longer available. Please contact the administrator.';
                accessStatusDescription.textContent = 'This access session has ended.';

                pendingSpinner.classList.add('d-none');
            }
        }

        async function checkVisitorAccess() {

            try {

                const response = await fetch('api/check-access.php', {
                    cache: 'no-store'
                });

                const data = await response.json();

                if (data.status === 'Active') {

                    clearInterval(accessCheckInterval);
                    window.location.href = 'maps.php';

                } else if (['Pending', 'Inactive', 'Expired', 'Ended'].includes(data.status)) {

                    showAccessScreen(data.status);

                    if (data.status === 'Expired' || data.status === 'Ended') {
                        clearInterval(accessCheckInterval);
                    }

                }

            } catch (error) {
                console.log('Access checking failed:', error);
            }
        }

        function startAccessMonitoring() {

            if (accessCheckInterval) {
                clearInterval(accessCheckInterval);
            }

            checkVisitorAccess();

            accessCheckInterval = setInterval(checkVisitorAccess, 3000);
        }

        // Language Selection
        document.querySelectorAll('.language-option').forEach(button => {

            button.addEventListener('click', function () {

                const selectedLanguage = this.dataset.language;

                sessionStorage.setItem('selectedLanguage', selectedLanguage);

                showAccessScreen('Pending');
                startAccessMonitoring();

            });

        });

        // Restore visitor access screen after deactivation or page refresh
        const selectedLanguage = sessionStorage.getItem('selectedLanguage');

        if (selectedLanguage) {
            startAccessMonitoring();
        }
    </script>

</body>
</html>
