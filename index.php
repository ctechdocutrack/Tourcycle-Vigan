
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
                window.location.href = 'maps.php';
            };

        }
    </script>

</body>
</html>
