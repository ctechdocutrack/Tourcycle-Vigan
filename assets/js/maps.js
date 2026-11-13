document.addEventListener('DOMContentLoaded', function () {

    // ==========================================
    // 1. INITIALIZE MAP
    // ==========================================

    const touristMap = L.map('touristMap', {
        zoomControl: false,
        rotate: true,
        bearing: 0,
        pitch: 0,
        rotateControl: false,
        touchRotate: false
    }).setView([17.5747, 120.3869], 15);

    L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://stadiamaps.com/attribution/">Stadia Maps</a> &copy; OpenMapTiles &copy; OpenStreetMap',
        maxZoom: 20
    }).addTo(touristMap);

    L.control.zoom({
        position: 'bottomleft'
    }).addTo(touristMap);

    const userLatitude = sessionStorage.getItem('userLatitude');
    const userLongitude = sessionStorage.getItem('userLongitude');

    if (userLatitude && userLongitude) {
        touristMap.setView([
            parseFloat(userLatitude),
            parseFloat(userLongitude)
        ], 16);
    }


    // ==========================================
    // 2. DESTINATION DATA
    // ==========================================

    const destinations = [
        {
            name: 'Calle Crisologo',
            category: 'Historical Site',
            coordinates: [17.5747, 120.3869],
            image: 'assets/images/calle-crisologo.jpg',
            description: 'Calle Crisologo is one of the most famous heritage streets in Vigan City. It is known for its Spanish colonial houses, cobblestone streets, antique shops, and traditional architecture.',
            history: 'The street preserves the architectural character of the Spanish colonial period. Its heritage houses reflect the cultural and historical development of Vigan.'
        },
        {
            name: 'Plaza Salcedo',
            category: 'Historical Landmark',
            coordinates: [17.5748, 120.3876],
            image: 'assets/images/plaza-salcedo.jpg',
            description: 'Plaza Salcedo is a historic public plaza located in the heart of Vigan City. It is known for its dancing fountain and surrounding historical landmarks.',
            history: 'The plaza is named after Juan de Salcedo, who established the Spanish settlement in Vigan in 1572. It has served as an important public space throughout the city’s history.'
        },
        {
            name: 'Bantay Bell Tower',
            category: 'Historical Landmark',
            coordinates: [17.5845, 120.3895],
            image: 'assets/images/bantay-bell-tower.jpg',
            description: 'Bantay Bell Tower is a famous historical landmark located in Bantay, Ilocos Sur, near Vigan City.',
            history: 'The bell tower is associated with the historic St. Augustine Parish Church. It is one of the recognizable heritage landmarks in the area.'
        },
        {
            name: 'Vigan Cathedral',
            category: 'Church',
            coordinates: [17.5744, 120.3870],
            image: 'assets/images/vigan-cathedral.jpg',
            description: 'Vigan Cathedral, also known as St. Paul Metropolitan Cathedral, is a historic Catholic church located near Plaza Salcedo in Vigan City.',
            history: 'The cathedral is an important religious and historical landmark in Vigan. Its present structure reflects the Spanish colonial influence on the architecture and religious heritage of the city.'
        },
        {
            name: 'Syquia Mansion',
            category: 'Historical Landmark',
            coordinates: [17.57103, 120.3899],
            image: 'assets/images/syquia-mansion.jpg',
            description: 'Syquia Mansion is a historic ancestral house in Vigan City. It is now a museum showcasing the architecture, furniture, family heirlooms, and historical artifacts of the Syquia family.',
            history: 'The original structure was built in 1830 by Don Justo Angco. In 1875, it was given as a dowry to his daughter Estefania when she married Gregorio Syquia. The mansion is also connected to former Philippine President Elpidio Quirino, who married Alicia Syquia.'
        }
    ];


    // ==========================================
    // 3. MAP VARIABLES
    // ==========================================

    const destinationMarkers = L.layerGroup().addTo(touristMap);

    let selectedDestination = null;
    let userLocation = null;
    let userMarker = null;
    let routeLayer = null;

    let navigationWatchId = null;
    let navigationActive = false;
    let routeCoordinates = [];
    let routeDistanceMeters = 0;
    let routeDurationSeconds = 0;
    let previousUserLocation = null;
    let currentHeading = 0;

    // Restore previously detected location
    if (userLatitude && userLongitude) {
        userLocation = [
            parseFloat(userLatitude),
            parseFloat(userLongitude)
        ];

        userMarker = L.marker(userLocation)
            .addTo(touristMap)
            .bindPopup('<strong>Your Current Location</strong>');
    }


    // ==========================================
    // 4. DESTINATION INFORMATION PANEL
    // ==========================================

    const destinationPanel = document.getElementById('mapDestinationPanel');

    function selectDestination(destination) {

        selectedDestination = destination;

        mapSearch.value = destination.name;
        searchResults.classList.add('d-none');

        document.getElementById('mapDestinationImage').src = destination.image;
        document.getElementById('mapDestinationImage').alt = destination.name;

        document.getElementById('mapDestinationName').textContent = destination.name;

        document.getElementById('mapDestinationCategory').textContent = destination.category;

        document.getElementById('mapDestinationDescription').textContent = destination.description;

        document.getElementById('destinationCoordinates').textContent =
            `Latitude: ${destination.coordinates[0]}, Longitude: ${destination.coordinates[1]}`;

        document.getElementById('audioStatus').textContent = 'Audio guide is ready.';

        document.getElementById('routeStatus').textContent =
            'Ready to calculate your route.';

        window.speechSynthesis.cancel();

        destinationPanel.classList.remove('d-none');

        touristMap.flyTo(destination.coordinates, 17, {
            duration: 1
        });
    }


    // Close Destination Panel
    document.getElementById('closeMapPanel').addEventListener('click', function () {

        destinationPanel.classList.add('d-none');

        window.speechSynthesis.cancel();

    });


    // ==========================================
    // 5. DESTINATION MARKERS
    // ==========================================

    
    function renderMapMarkers() {

        destinationMarkers.clearLayers();

        destinations.forEach(destination => {

            const markerIcon = L.divIcon({
                className: 'attraction-marker',
                html: `
                    <div class="attraction-marker-wrapper">
                        <div class="attraction-icon">
                            <i class="bi bi-geo-alt-fill"></i>
                        </div>
                        <span class="attraction-label">${destination.name}</span>
                    </div>
                `,
                iconSize: [0, 0],
                iconAnchor: [0, 0]
            });

            const marker = L.marker(destination.coordinates, {
                icon: markerIcon
            }).addTo(destinationMarkers);

            marker.on('click', function () {
                selectDestination(destination);
            });

        });

    }


    renderMapMarkers();


    // ==========================================
    // 6. MAP SEARCH
    // ==========================================

    const mapSearch = document.getElementById('mapSearch');
    const searchResults = document.getElementById('mapSearchResults');

    // AUTO OPEN DESTINATION FROM URL
    const urlParams = new URLSearchParams(window.location.search);
    const destinationSlug = urlParams.get('destination');

    if (destinationSlug) {
        const destination = destinations.find(item =>
            item.name.toLowerCase().replace(/\s+/g, '-') === destinationSlug
        );

        if (destination) {
            selectDestination(destination);
        }
    }

    function renderSearchResults(searchValue) {

        const filteredDestinations = destinations.filter(destination =>
            destination.name.toLowerCase().includes(searchValue) ||
            destination.category.toLowerCase().includes(searchValue)
        );

        searchResults.innerHTML = '';

        if (!searchValue) {
            searchResults.classList.add('d-none');
            return;
        }

        if (filteredDestinations.length === 0) {

            searchResults.innerHTML = `
                <div class="p-3 text-muted small">
                    No destinations found.
                </div>
            `;

        } else {

            filteredDestinations.forEach(destination => {

                const result = document.createElement('button');

                result.type = 'button';
                result.className = 'btn btn-light w-100 text-start rounded-0 border-0 p-3';

                result.innerHTML = `
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-geo-alt-fill text-success fs-5"></i>
                        <div>
                            <div class="fw-semibold">${destination.name}</div>
                            <small class="text-muted">${destination.category}</small>
                        </div>
                    </div>
                `;

                result.addEventListener('click', function () {

                    selectDestination(destination);

                    searchResults.classList.add('d-none');

                    mapSearch.value = destination.name;

                });

                searchResults.appendChild(result);

            });

        }

        searchResults.classList.remove('d-none');
    }

    mapSearch.addEventListener('input', function () {

        renderSearchResults(this.value.toLowerCase().trim());

    });

    document.getElementById('clearMapSearch').addEventListener('click', function () {

        // Clear Search Bar
        mapSearch.value = '';

        // Hide Search Results
        searchResults.classList.add('d-none');

        // Close Destination Information Panel
        destinationPanel.classList.add('d-none');

        // Stop Audio Narration
        window.speechSynthesis.cancel();

        // Reset Selected Destination
        selectedDestination = null;

        mapSearch.focus();

    });

    document.addEventListener('click', function (event) {

        if (!event.target.closest('#mapSearchContainer')) {
            searchResults.classList.add('d-none');
        }

    });


    // ==========================================
    // 7. USER CURRENT LOCATION
    // ==========================================

    function locateUser() {

        if (!navigator.geolocation) {

            alert('Geolocation is not supported by your browser.');

            return;
        }

        const locationButton = document.getElementById('locateMeMap');

        locationButton.disabled = true;

        locationButton.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Detecting...
        `;

        navigator.geolocation.getCurrentPosition(

            function (position) {

                const latitude = position.coords.latitude;
                const longitude = position.coords.longitude;

                userLocation = [latitude, longitude];

                if (userMarker) {
                    touristMap.removeLayer(userMarker);
                }

                userMarker = L.marker(userLocation)
                    .addTo(touristMap)
                    .bindPopup('<strong>You are here!</strong>')
                    .openPopup();

                touristMap.flyTo(userLocation, 17, {
                    duration: 1
                });

                locationButton.disabled = false;

                locationButton.innerHTML = `
                    <i class="bi bi-crosshair me-2"></i>
                    My Location
                `;

            },

            function (error) {

                let errorMessage = 'Unable to detect your location.';

                if (error.code === 1) {
                    errorMessage = 'Location permission denied. Please allow location access.';
                } else if (error.code === 2) {
                    errorMessage = 'Your current location is unavailable.';
                } else if (error.code === 3) {
                    errorMessage = 'Location request timed out. Please try again.';
                }

                alert(errorMessage);

                locationButton.disabled = false;

                locationButton.innerHTML = `
                    <i class="bi bi-crosshair me-2"></i>
                    My Location
                `;

            },

            {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            }

        );

    }

    document.getElementById('locateMeMap').addEventListener('click', locateUser);


    // ==========================================
    // 8. AUDIO TOUR GUIDE
    // ==========================================

    const audioLanguage = document.getElementById('audioLanguage');
    const audioStatus = document.getElementById('audioStatus');

    document.getElementById('playAudio').addEventListener('click', function () {

        if (!selectedDestination) {

            audioStatus.textContent = 'Please select a destination first.';

            return;
        }

        if (!('speechSynthesis' in window)) {

            audioStatus.textContent = 'Your browser does not support audio narration.';

            return;
        }

        window.speechSynthesis.cancel();

        const narration = new SpeechSynthesisUtterance(
            `${selectedDestination.name}. 
            ${selectedDestination.description}
            Historical Background.
            ${selectedDestination.history}`
        );

        narration.lang = audioLanguage.value;
        narration.rate = 0.9;
        narration.pitch = 1;

        narration.onstart = function () {

            audioStatus.textContent = 'Playing audio narration...';

        };

        narration.onend = function () {

            audioStatus.textContent = 'Audio narration completed.';

        };

        narration.onerror = function () {

            audioStatus.textContent = 'Unable to play audio narration.';

        };

        window.speechSynthesis.speak(narration);

    });


    // Stop Audio
    document.getElementById('stopAudio').addEventListener('click', function () {

        window.speechSynthesis.cancel();

        audioStatus.textContent = 'Audio narration stopped.';

    });


    
    // ==========================================
    // 9. ROUTE GUIDANCE
    // ==========================================

    document.getElementById('showRoute').addEventListener('click', async function () {

        const routeStatus = document.getElementById('routeStatus');
        const button = this;

        if (!selectedDestination) {
            routeStatus.textContent = 'Please select a destination first.';
            return;
        }

        // Automatically detect current location if not yet available
        if (!userLocation) {

            button.disabled = true;
            button.innerHTML = `
                <span class="spinner-border spinner-border-sm me-2"></span>
                Detecting Location...
            `;

            routeStatus.textContent = 'Detecting your current location...';

            if (!navigator.geolocation) {
                routeStatus.textContent = 'Geolocation is not supported by your browser.';
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-map me-2"></i> Show Route';
                return;
            }

            try {

                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(
                        resolve,
                        reject,
                        {
                            enableHighAccuracy: true,
                            timeout: 15000,
                            maximumAge: 0
                        }
                    );
                });

                userLocation = [
                    position.coords.latitude,
                    position.coords.longitude
                ];

                if (userMarker) {
                    touristMap.removeLayer(userMarker);
                }

                userMarker = L.marker(userLocation)
                    .addTo(touristMap)
                    .bindPopup('<strong>You are here!</strong>');

            } catch (error) {

                let errorMessage = 'Unable to detect your location.';

                if (error.code === 1) {
                    errorMessage = 'Location permission denied. Please allow location access.';
                } else if (error.code === 2) {
                    errorMessage = 'Your current location is unavailable.';
                } else if (error.code === 3) {
                    errorMessage = 'Location request timed out. Please try again.';
                }

                routeStatus.textContent = errorMessage;
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-map me-2"></i> Show Route';
                return;
            }
        }

        // Calculate route
        button.disabled = true;
        button.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Calculating Route...
        `;

        routeStatus.textContent = 'Calculating your route...';

        const startLongitude = userLocation[1];
        const startLatitude = userLocation[0];

        const endLongitude = selectedDestination.coordinates[1];
        const endLatitude = selectedDestination.coordinates[0];

        const routeUrl =
            `https://router.project-osrm.org/route/v1/driving/${startLongitude},${startLatitude};${endLongitude},${endLatitude}?overview=full&geometries=geojson`;

        try {

            const response = await fetch(routeUrl);

            if (!response.ok) {
                throw new Error('Unable to retrieve route.');
            }

            const data = await response.json();

            if (data.code !== 'Ok' || !data.routes.length) {
                throw new Error('No route found.');
            }

            const route = data.routes[0];

            if (routeLayer) {
                touristMap.removeLayer(routeLayer);
            }

            routeCoordinates = route.geometry.coordinates.map(
                coordinate => [coordinate[1], coordinate[0]]
            );

            routeDistanceMeters = route.distance;
            routeDurationSeconds = route.duration;

            routeLayer = L.polyline(routeCoordinates, {
                color: '#0d6efd',
                weight: 6,
                opacity: 0.85
            }).addTo(touristMap);

            touristMap.fitBounds(routeLayer.getBounds(), {
                padding: [50, 50]
            });

            const distanceKm = (route.distance / 1000).toFixed(2);
            const durationMinutes = Math.round(route.duration / 60);
            const hours = Math.floor(durationMinutes / 60);
            const minutes = durationMinutes % 60;

            const durationText =
                `${hours > 0 ? hours + ' hour(s) ' : ''}${minutes} minute(s)`;

            routeStatus.innerHTML = `
                <strong>Destination:</strong> ${selectedDestination.name}<br>
                <strong>Distance:</strong> ${distanceKm} km<br>
                <strong>Estimated Travel Time:</strong> ${durationText}
            `;

            button.innerHTML = `
                <i class="bi bi-check-circle me-2"></i>
                Route Displayed
            `;

            document.getElementById('startNavigation').classList.remove('d-none');
            document.getElementById('navigationStatus').classList.add('d-none');

        } catch (error) {

            console.error('Route error:', error);

            routeStatus.textContent =
                'Unable to calculate route. Please check your internet connection and try again.';

            button.innerHTML = '<i class="bi bi-map me-2"></i> Show Route';

        }

        button.disabled = false;

    });

    // ==========================================
    // 10. LIVE NAVIGATION
    // ==========================================

    const startNavigationButton = document.getElementById('startNavigation');
    const endNavigationButton = document.getElementById('endNavigation');
    const navigationStatus = document.getElementById('navigationStatus');

    function calculateDistance(pointA, pointB) {

        const earthRadius = 6371000;
        const toRadians = degrees => degrees * Math.PI / 180;

        const latitudeDifference = toRadians(pointB[0] - pointA[0]);
        const longitudeDifference = toRadians(pointB[1] - pointA[1]);

        const a =
            Math.sin(latitudeDifference / 2) ** 2 +
            Math.cos(toRadians(pointA[0])) *
            Math.cos(toRadians(pointB[0])) *
            Math.sin(longitudeDifference / 2) ** 2;

        return earthRadius * 2 * Math.atan2(
            Math.sqrt(a),
            Math.sqrt(1 - a)
        );
    }


    function updateNavigationProgress() {

        if (!userLocation || !routeCoordinates.length) {
            return;
        }

        // Find the nearest point along the route
        let nearestIndex = 0;
        let nearestDistance = Infinity;

        routeCoordinates.forEach((coordinate, index) => {

            const distance = calculateDistance(userLocation, coordinate);

            if (distance < nearestDistance) {
                nearestDistance = distance;
                nearestIndex = index;
            }

        });

        // Calculate remaining route distance
        let remainingDistance = 0;

        for (let i = nearestIndex; i < routeCoordinates.length - 1; i++) {
            remainingDistance += calculateDistance(
                routeCoordinates[i],
                routeCoordinates[i + 1]
            );
        }

        const averageSpeed = routeDurationSeconds / routeDistanceMeters;
        const remainingSeconds = remainingDistance * averageSpeed;

        const remainingMinutes = Math.ceil(remainingSeconds / 60);
        const remainingKm = (remainingDistance / 1000).toFixed(2);

        navigationStatus.innerHTML = `
            <strong><i class="bi bi-navigation me-1"></i> Navigation Active</strong><br>
            Distance remaining: ${remainingKm} km<br>
            Estimated time: ${remainingMinutes} minute(s)
        `;

        // Check if the user has arrived
        const destinationDistance = calculateDistance(
            userLocation,
            selectedDestination.coordinates
        );

        if (destinationDistance <= 30) {
            stopNavigation(`You have arrived at ${selectedDestination.name}!`);
        }

    }
    // ==========================================
    // PHONE COMPASS
    // ==========================================

    let compassAvailable = false;
    let compassListenerRegistered = false;

    function handleDeviceOrientation(event) {

        let heading = null;

        // iPhone / iPad compass heading
        if (typeof event.webkitCompassHeading === 'number') {
            heading = event.webkitCompassHeading;
        }

        // Android devices supporting absolute orientation
        else if (event.absolute && typeof event.alpha === 'number') {
            heading = (360 - event.alpha) % 360;
        }

        if (heading === null || !Number.isFinite(heading)) {
            return;
        }

        currentHeading = heading;
        compassAvailable = true;

        if (navigationActive) {
            touristMap.setBearing(-currentHeading);
        }
    }


    async function enablePhoneCompass() {

        if (!window.DeviceOrientationEvent) {
            return false;
        }

        try {

            // Required by some iPhones and iPads
            if (typeof DeviceOrientationEvent.requestPermission === 'function') {

                const permission = await DeviceOrientationEvent.requestPermission();

                if (permission !== 'granted') {
                    return false;
                }

            }

            if (!compassListenerRegistered) {
                window.addEventListener(
                    'deviceorientation',
                    handleDeviceOrientation
                );

                compassListenerRegistered = true;
            }

            return true;

        } catch (error) {

            console.error('Compass error:', error);
            return false;

        }

    }

    function createDirectionalUserIcon() {

        return L.divIcon({
            className: 'navigation-user-marker',
            html: `
                <div class="navigation-user-icon active">
                    <div class="navigation-direction-cone"></div>

                    <div class="navigation-arrow">
                        <i class="bi bi-navigation-fill"></i>
                    </div>
                </div>
            `,
            iconSize: [80, 80],
            iconAnchor: [40, 40]
        });

    }

    function updateNavigationCamera(location) {

        // Rotate map based on phone direction
        touristMap.setBearing(-currentHeading);

        // Apply 3D-style perspective
        touristMap.setPitch(45);

        // Keep user marker near the lower-center
        touristMap.setView(location, 18, {
            animate: false
        });

        const verticalOffset = touristMap.getSize().y * 0.22;

        touristMap.panBy([0, verticalOffset], {
            animate: false
        });

    }

    // Calculate direction based on two GPS locations
    function calculateBearing(start, end) {

        const toRadians = degrees => degrees * Math.PI / 180;
        const toDegrees = radians => radians * 180 / Math.PI;

        const latitude1 = toRadians(start[0]);
        const latitude2 = toRadians(end[0]);
        const longitudeDifference = toRadians(end[1] - start[1]);

        const y = Math.sin(longitudeDifference) * Math.cos(latitude2);

        const x =
            Math.cos(latitude1) * Math.sin(latitude2) -
            Math.sin(latitude1) * Math.cos(latitude2) *
            Math.cos(longitudeDifference);

        return (toDegrees(Math.atan2(y, x)) + 360) % 360;

    }

    function startNavigation() {

        if (!routeCoordinates.length || !selectedDestination) {
            navigationStatus.textContent = 'Please calculate a route first.';
            navigationStatus.classList.remove('d-none');
            return;
        }

        if (!navigator.geolocation) {
            navigationStatus.textContent = 'Geolocation is not supported by your browser.';
            navigationStatus.classList.remove('d-none');
            return;
        }

        if (navigationActive) {
            return;
        }

        navigationActive = true;

        startNavigationButton.classList.add('d-none');
        endNavigationButton.classList.remove('d-none');
        navigationStatus.classList.remove('d-none');

        navigationStatus.textContent = 'Starting live GPS tracking...';

        navigationWatchId = navigator.geolocation.watchPosition(

            function (position) {

                const newLocation = [
                    position.coords.latitude,
                    position.coords.longitude
                ];

                // Use GPS heading only when phone compass is unavailable
                if (!compassAvailable) {

                    if (position.coords.heading !== null &&
                        Number.isFinite(position.coords.heading)) {

                        currentHeading = position.coords.heading;

                    } else if (previousUserLocation &&
                        calculateDistance(previousUserLocation, newLocation) > 2) {

                        currentHeading = calculateBearing(previousUserLocation, newLocation);

                    }

                }

                userLocation = newLocation;
                previousUserLocation = newLocation;

                // Create or update directional marker
                if (!userMarker) {

                    userMarker = L.marker(userLocation, {
                        icon: createDirectionalUserIcon(currentHeading),
                        zIndexOffset: 1000
                    }).addTo(touristMap);

                } else {

                    userMarker.setLatLng(userLocation);
                    userMarker.setIcon(createDirectionalUserIcon(currentHeading));

                }

                userMarker.bindPopup('<strong>Your Current Location</strong>');

                // Update third-person navigation camera
                updateNavigationCamera(userLocation);

                updateNavigationProgress();

            },

            function (error) {

                let message = 'Unable to update your location.';

                if (error.code === 1) {
                    message = 'Location permission denied. Please allow GPS access.';
                } else if (error.code === 2) {
                    message = 'GPS signal is unavailable. Try moving to an open area.';
                } else if (error.code === 3) {
                    message = 'GPS request timed out. Waiting for another location update.';
                }

                navigationStatus.textContent = message;

            },

            {
                enableHighAccuracy: true,
                maximumAge: 1000,
                timeout: 15000
            }

        );

    }


    function stopNavigation(message = 'Navigation stopped.') {

        if (navigationWatchId !== null) {
            navigator.geolocation.clearWatch(navigationWatchId);
            navigationWatchId = null;
        }

        navigationActive = false;

        // Restore normal map orientation
        touristMap.setBearing(0);
        touristMap.setPitch(0);

        if (userLocation) {
            touristMap.setView(userLocation, 17);
        }

        endNavigationButton.classList.add('d-none');

        if (routeCoordinates.length && selectedDestination) {
            startNavigationButton.classList.remove('d-none');
        } else {
            startNavigationButton.classList.add('d-none');
        }

        navigationStatus.classList.remove('d-none');
        navigationStatus.textContent = message;
    }


    startNavigationButton.addEventListener('click', async function () {

        navigationStatus.classList.remove('d-none');
        navigationStatus.textContent = 'Activating phone compass...';

        const compassEnabled = await enablePhoneCompass();

        if (!compassEnabled) {
            navigationStatus.textContent =
                'Compass unavailable. Using GPS movement direction instead.';
        }

        startNavigation();

    });

    endNavigationButton.addEventListener('click', stopNavigation);



    // ==========================================
    // 10. MAP CLEANUP
    // ==========================================

    window.addEventListener('resize', function () {

        touristMap.invalidateSize();

    });

    // Fix map rendering after initial page load
    setTimeout(function () {

        touristMap.invalidateSize();

    }, 300);

});