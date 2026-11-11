
// Initialize the map centered on Vigan City
const touristMap = L.map('touristMap').setView([17.5747, 120.3869], 15);

// Load OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
    maxZoom: 19
}).addTo(touristMap);

// Tourist destinations
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

// Render Tourist Destination Cards
function renderDestinationCards() {
    const destinationCardsContainer = document.getElementById('destinationCardsContainer');

    destinationCardsContainer.innerHTML = destinations.map(destination => `
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0">
                <img src="${destination.image}" class="card-img-top" alt="${destination.name}" style="height: 220px; object-fit: cover;">

                <div class="card-body">
                    <span class="badge bg-warning text-dark mb-2">${destination.category}</span>
                    <h5 class="card-title fw-bold">${destination.name}</h5>
                    <p class="card-text text-muted">${destination.description}</p>

                    <button class="btn btn-outline-success btn-sm view-details" data-destination="${destination.name}">
                        <i class="bi bi-info-circle me-1"></i> View Details
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

// Display Destination Cards
renderDestinationCards();


// Add destination markers
destinations.forEach(destination => {
    L.marker(destination.coordinates)
        .addTo(touristMap)
        .bindPopup(`
            <strong>${destination.name}</strong>
            <p class="mb-0">${destination.description}</p>
        `);
});

// User location and route variables
let userMarker = null;
let userLocation = null;
let routeLayer = null;

// Find current location
document.getElementById('locateMe').addEventListener('click', function () {

    const locationStatus = document.getElementById('locationStatus');

    if (!navigator.geolocation) {
        locationStatus.textContent = 'Geolocation is not supported by your browser.';
        return;
    }

    locationStatus.textContent = 'Detecting your location...';

    navigator.geolocation.getCurrentPosition(
        function (position) {

            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;
            userLocation = [latitude, longitude];
            renderNearbyDestinations();

            if (userMarker) {
                touristMap.removeLayer(userMarker);
            }

            userMarker = L.marker([latitude, longitude])
                .addTo(touristMap)
                .bindPopup('You are here!')
                .openPopup();

            touristMap.setView([latitude, longitude], 17);

            locationStatus.textContent =
                `Latitude: ${latitude.toFixed(6)}, Longitude: ${longitude.toFixed(6)}`;
        },
        function (error) {

            locationStatus.textContent =
                'Unable to detect your location. Please allow location access and try again.';

        },
        {
            enableHighAccuracy: true,
            timeout: 10000
        }
    );
});


// Destination Details Modal

const destinationModal = new bootstrap.Modal(
    document.getElementById('destinationModal')
);

document.querySelectorAll('.view-details').forEach(button => {
    button.addEventListener('click', function () {

        const destinationName = this.dataset.destination;

        const destination = destinations.find(
            item => item.name === destinationName
        );

        if (!destination) return;

        currentDestination = destination;
        window.speechSynthesis.cancel();
        document.getElementById('audioStatus').textContent = 'Audio guide is ready.';

        document.getElementById('destinationTitle').textContent = destination.name;
        document.getElementById('destinationCategory').textContent = destination.category;
        document.getElementById('destinationImage').src = destination.image;
        document.getElementById('destinationImage').alt = destination.name;
        document.getElementById('destinationDescription').textContent = destination.description;
        document.getElementById('destinationHistory').textContent = destination.history;

        document.getElementById('destinationCoordinates').textContent =
            `Latitude: ${destination.coordinates[0]}, Longitude: ${destination.coordinates[1]}`;

        destinationModal.show();
    });
});


// Audio Tour Guide
let currentDestination = null;

document.getElementById('playAudio').addEventListener('click', function () {

    if (!currentDestination) return;

    if (!('speechSynthesis' in window)) {
        document.getElementById('audioStatus').textContent =
            'Your browser does not support audio narration.';
        return;
    }

    window.speechSynthesis.cancel();

    const language = document.getElementById('audioLanguage').value;

    const narration = new SpeechSynthesisUtterance(
        `${currentDestination.name}. 
        ${currentDestination.description} 
        Historical Background. 
        ${currentDestination.history}`
    );

    narration.lang = language;
    narration.rate = 0.9;
    narration.pitch = 1;

    narration.onstart = function () {
        document.getElementById('audioStatus').textContent =
            'Playing audio narration...';
    };

    narration.onend = function () {
        document.getElementById('audioStatus').textContent =
            'Audio narration completed.';
    };

    narration.onerror = function () {
        document.getElementById('audioStatus').textContent =
            'Unable to play audio narration.';
    };

    window.speechSynthesis.speak(narration);
});

// Stop Audio
document.getElementById('stopAudio').addEventListener('click', function () {

    window.speechSynthesis.cancel();

    document.getElementById('audioStatus').textContent =
        'Audio narration stopped.';
});


// Route Guidance
document.getElementById('showRoute').addEventListener('click', async function () {

    const routeStatus = document.getElementById('routeStatus');

    if (!currentDestination) {
        routeStatus.textContent = 'Please select a destination first.';
        return;
    }

    if (!userLocation) {
        routeStatus.textContent = 'Please detect your current location first.';
        return;
    }

    const destinationCoordinates = currentDestination.coordinates;

    const startLongitude = userLocation[1];
    const startLatitude = userLocation[0];

    const endLongitude = destinationCoordinates[1];
    const endLatitude = destinationCoordinates[0];

    const routeUrl = `https://router.project-osrm.org/route/v1/driving/${startLongitude},${startLatitude};${endLongitude},${endLatitude}?overview=full&geometries=geojson`;

    routeStatus.textContent = 'Calculating your route...';

    try {

        const response = await fetch(routeUrl);
        const data = await response.json();

        if (!response.ok || data.code !== 'Ok' || !data.routes.length) {
            throw new Error('No route found.');
        }

        const route = data.routes[0];

        // Remove previous route
        if (routeLayer) {
            touristMap.removeLayer(routeLayer);
        }

        // Draw route on map
        const routeCoordinates = route.geometry.coordinates.map(
            coordinate => [coordinate[1], coordinate[0]]
        );

        routeLayer = L.polyline(routeCoordinates, {
            color: '#0d6efd',
            weight: 6,
            opacity: 0.8
        }).addTo(touristMap);

        // Fit map to route
        touristMap.fitBounds(routeLayer.getBounds(), {
            padding: [30, 30]
        });

        // // Calculate distance and duration
        // const distanceKm = (route.distance / 1000).toFixed(2);
        // const durationMinutes = Math.round(route.duration / 60);

        // routeStatus.innerHTML = `
        //     <strong>Destination:</strong> ${currentDestination.name}<br>
        //     <strong>Distance:</strong> ${distanceKm} km<br>
        //     <strong>Estimated travel time:</strong> ${durationMinutes} minutes
        // `;

        // // Close modal to show the map
        // destinationModal.hide();

        // Calculate distance and duration
        const distanceKm = (route.distance / 1000).toFixed(2);
        const durationMinutes = Math.round(route.duration / 60);

        const hours = Math.floor(durationMinutes / 60);
        const minutes = durationMinutes % 60;

        document.getElementById('routeDestination').textContent =
            `Destination: ${currentDestination.name}`;

        document.getElementById('routeDistance').textContent =
            `Distance: ${distanceKm} km`;

        document.getElementById('routeDuration').textContent =
            `Estimated Travel Time: ${hours > 0 ? hours + ' hour(s) ' : ''}${minutes} minute(s)`;

        document.getElementById('routeInfo').classList.remove('d-none');

        // Close modal and show map
        destinationModal.hide();

        // Scroll to map section
        document.getElementById('map').scrollIntoView({
            behavior: 'smooth'
        });


    } catch (error) {

        routeStatus.textContent =
            'Unable to calculate route. Please check your internet connection and try again.';

        console.error('Route error:', error);

    }
});


// Calculate distance between two GPS coordinates
function calculateDistance(lat1, lon1, lat2, lon2) {

    const earthRadius = 6371;

    const latitudeDifference = (lat2 - lat1) * Math.PI / 180;
    const longitudeDifference = (lon2 - lon1) * Math.PI / 180;

    const calculation =
        Math.sin(latitudeDifference / 2) ** 2 +
        Math.cos(lat1 * Math.PI / 180) *
        Math.cos(lat2 * Math.PI / 180) *
        Math.sin(longitudeDifference / 2) ** 2;

    const centralAngle = 2 * Math.atan2(
        Math.sqrt(calculation),
        Math.sqrt(1 - calculation)
    );

    return earthRadius * centralAngle;
}


// Display nearby tourist attractions
function renderNearbyDestinations() {

    const nearbyContainer = document.getElementById('nearbyDestinations');
    const nearbyStatus = document.getElementById('nearbyStatus');

    if (!userLocation) {
        nearbyStatus.textContent = 'Please detect your current location first.';
        return;
    }

    const nearbyPlaces = destinations.map(destination => {

        const distance = calculateDistance(
            userLocation[0],
            userLocation[1],
            destination.coordinates[0],
            destination.coordinates[1]
        );

        return {
            ...destination,
            distance: distance
        };

    });

    nearbyPlaces.sort((first, second) => first.distance - second.distance);

    nearbyContainer.innerHTML = '';

    nearbyStatus.textContent =
        `Found ${nearbyPlaces.length} tourist attractions, sorted by distance.`;

    nearbyPlaces.forEach((destination, index) => {

        const distanceText = destination.distance < 1
            ? `${(destination.distance * 1000).toFixed(0)} meters away`
            : `${destination.distance.toFixed(2)} km away`;

        nearbyContainer.innerHTML += `
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm">
                    <img src="${destination.image}" class="card-img-top img-fluid" alt="${destination.name}" loading="lazy" style="height: 220px; object-fit: cover;">
                    <div class="card-body">

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-success">
                                ${index === 0 ? 'Nearest Attraction' : 'Tourist Attraction'}
                            </span>
                            <i class="bi bi-geo-alt-fill text-success fs-4"></i>
                        </div>

                        <h5 class="fw-bold">${destination.name}</h5>

                        <p class="text-muted small mb-3">
                            ${destination.description}
                        </p>

                        <div class="border-top pt-3">
                            <i class="bi bi-signpost text-success me-2"></i>
                            <strong>${distanceText}</strong>
                        </div>

                        <button class="btn btn-outline-success btn-sm w-100 mt-3 view-details"
                            data-destination="${destination.name}">
                            <i class="bi bi-info-circle me-1"></i>
                            View Details
                        </button>

                    </div>
                </div>
            </div>
        `;

    });

    // Reconnect View Details buttons to the existing modal
    nearbyContainer.querySelectorAll('.view-details').forEach(button => {

        button.addEventListener('click', function () {

            const destination = destinations.find(
                item => item.name === this.dataset.destination
            );

            if (!destination) return;

            currentDestination = destination;

            window.speechSynthesis.cancel();

            document.getElementById('audioStatus').textContent = 'Audio guide is ready.';
            document.getElementById('destinationTitle').textContent = destination.name;
            document.getElementById('destinationCategory').textContent = destination.category;
            document.getElementById('destinationImage').src = destination.image;
            document.getElementById('destinationImage').alt = destination.name;
            document.getElementById('destinationDescription').textContent = destination.description;
            document.getElementById('destinationHistory').textContent = destination.history;

            document.getElementById('destinationCoordinates').textContent =
                `Latitude: ${destination.coordinates[0]}, Longitude: ${destination.coordinates[1]}`;

            destinationModal.show();

        });

    });

}

// Destination Search and Category Filter
const destinationSearch = document.getElementById('destinationSearch');
const destinationCategoryFilter = document.getElementById('destinationCategoryFilter');
const destinationCards = document.querySelectorAll('#destinations .row.g-4 > div');

function filterDestinations() {
    const searchValue = destinationSearch.value.toLowerCase().trim();
    const selectedCategory = destinationCategoryFilter.value.toLowerCase();
    let visibleCount = 0;

    destinationCards.forEach(card => {
        const destinationName = card.querySelector('.card-title').textContent.toLowerCase();
        const destinationCategory = card.querySelector('.badge').textContent.toLowerCase();

        const matchesSearch = destinationName.includes(searchValue) || destinationCategory.includes(searchValue);
        const matchesCategory = selectedCategory === '' || destinationCategory === selectedCategory;

        const isVisible = matchesSearch && matchesCategory;

        card.style.display = isVisible ? '' : 'none';

        if (isVisible) {
            visibleCount++;
        }
    });

    document.getElementById('noDestinationsMessage').classList.toggle('d-none', visibleCount > 0);
}

destinationSearch.addEventListener('input', filterDestinations);
destinationCategoryFilter.addEventListener('change', filterDestinations);

// Load Vigan City Weather
async function loadWeather() {

    const weatherContent = document.getElementById('weatherContent');
    const weatherUpdated = document.getElementById('weatherUpdated');

    weatherContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-success" role="status"></div>
            <p class="text-muted mt-3 mb-0">Loading weather information...</p>
        </div>
    `;

    weatherUpdated.textContent = '';

    const latitude = 17.5747;
    const longitude = 120.3869;

    const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m&timezone=auto`;

    try {

        const response = await fetch(weatherUrl);

        if (!response.ok) {
            throw new Error('Weather data unavailable.');
        }

        const data = await response.json();
        const weather = data.current;

        const weatherConditions = {
            0: 'Clear Sky',
            1: 'Mainly Clear',
            2: 'Partly Cloudy',
            3: 'Overcast',
            45: 'Fog',
            48: 'Depositing Rime Fog',
            51: 'Light Drizzle',
            53: 'Moderate Drizzle',
            55: 'Dense Drizzle',
            61: 'Slight Rain',
            63: 'Moderate Rain',
            65: 'Heavy Rain',
            71: 'Slight Snow',
            73: 'Moderate Snow',
            75: 'Heavy Snow',
            80: 'Slight Rain Showers',
            81: 'Moderate Rain Showers',
            82: 'Violent Rain Showers',
            95: 'Thunderstorm',
            96: 'Thunderstorm with Hail',
            99: 'Heavy Thunderstorm with Hail'
        };

        const condition = weatherConditions[weather.weather_code] || 'Unknown Condition';

        weatherContent.innerHTML = `
            <div class="text-center mb-4">
                <h1 class="display-2 fw-bold text-success mb-0">
                    ${weather.temperature_2m}°C
                </h1>
                <h5 class="text-muted">${condition}</h5>
            </div>

            <div class="row g-3 text-center">
                <div class="col-6 col-md-3">
                    <div class="bg-light rounded p-3 h-100">
                        <i class="bi bi-thermometer-half text-danger fs-2"></i>
                        <p class="small text-muted mb-1">Feels Like</p>
                        <strong>${weather.apparent_temperature}°C</strong>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="bg-light rounded p-3 h-100">
                        <i class="bi bi-droplets text-primary fs-2"></i>
                        <p class="small text-muted mb-1">Humidity</p>
                        <strong>${weather.relative_humidity_2m}%</strong>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="bg-light rounded p-3 h-100">
                        <i class="bi bi-wind text-info fs-2"></i>
                        <p class="small text-muted mb-1">Wind Speed</p>
                        <strong>${weather.wind_speed_10m} km/h</strong>
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <div class="bg-light rounded p-3 h-100">
                        <i class="bi bi-cloud-rain text-primary fs-2"></i>
                        <p class="small text-muted mb-1">Precipitation</p>
                        <strong>${weather.precipitation} mm</strong>
                    </div>
                </div>
            </div>
        `;

        weatherUpdated.textContent =
            `Last updated: ${weather.time.replace('T', ' ')} (${data.timezone})`;

    } catch (error) {

        weatherContent.innerHTML = `
            <div class="alert alert-warning mb-0">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Unable to load weather information. Please check your internet connection.
            </div>
        `;

    }
}

// Initial Weather Loading
loadWeather();

// Refresh Weather Button
document.getElementById('refreshWeather').addEventListener('click', loadWeather);


// Close Mobile Offcanvas After Navigation
document.querySelectorAll('#mobileNavbar a[href^="#"]').forEach(link => {
    link.addEventListener('click', function () {

        const target = document.querySelector(this.getAttribute('href'));
        const offcanvasElement = document.getElementById('mobileNavbar');
        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);

        if (offcanvas) {
            offcanvas.hide();
        }

        if (target) {
            setTimeout(() => {
                target.scrollIntoView({ behavior: 'smooth' });
            }, 300);
        }
    });
});

