
document.addEventListener("DOMContentLoaded", function () {

    const destinationSearch = document.getElementById("destinationSearch");
    const destinationFilters = document.getElementById("destinationFilters");
    // const destinationItems = document.querySelectorAll(".destination-item");
    const destinationGrid = document.getElementById("destinationGrid");
    const destinationCount = document.getElementById("destinationCount");
    const noDestinations = document.getElementById("noDestinations");

    let activeFilter = "all";
    let destinations = [];

    async function loadDestinations() {

        try {

            const response = await fetch("api/destinations.php");
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message);
            }

            destinations = result.data;

            renderDestinationFilters();
            renderDestinations();

        } catch (error) {

            console.error("Error loading destinations:", error);

        }

    }

    function renderDestinationFilters() {

        destinationFilters.querySelectorAll('[data-filter]:not([data-filter="all"])').forEach(function (button) {
            button.remove();
        });

        const categories = [...new Set(
            destinations
                .map(destination => destination.category.trim().toLowerCase())
                .filter(category => category !== "")
        )].sort();

        categories.forEach(function (category) {

            const button = document.createElement("button");

            button.type = "button";
            button.className = "btn rounded-pill";
            button.style.setProperty("background-color", "#FFFFFF", "important");
            button.style.setProperty("border", "1px solid #38BDF8", "important");
            button.style.setProperty("color", "#0284C7", "important");
            button.dataset.filter = category;
            button.textContent = category.charAt(0).toUpperCase() + category.slice(1);

            destinationFilters.appendChild(button);

        });

    }

    function renderDestinations() {

        destinationGrid.innerHTML = "";

        destinations.forEach(function (destination) {

            const category = destination.category.toLowerCase();

            const card = document.createElement("div");

            card.className = "col-12 col-sm-6 col-xl-3 destination-item";
            card.dataset.category = category;
            card.dataset.name = destination.name.toLowerCase();

            card.innerHTML = `
                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                    <img src="${destination.image}"
                        class="card-img-top object-fit-cover"
                        height="190"
                        style="object-fit: cover;"
                        alt="${destination.name}">

                    <div class="card-body">
                        <span class="badge mb-2" style="background-color: rgba(56, 189, 248, 0.15); color: #0284C7;">${destination.category}</span>
                        <h5 class="fw-bold" style="color: #1E293B;">${destination.name}</h5>
                        <p class="small destination-description" style="color: #64748B; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">${destination.description}</p>
                        <button type="button" class="btn btn-link btn-sm p-0 show-description" style="color: #0284C7;">Show more</button>

                        <a href="maps.php?destination=${destination.id}"
                            class="btn w-100 rounded-pill mt-2" style="color: #0284C7; border: 1px solid #38BDF8;">
                            <i class="bi bi-map me-2"></i>View on Map
                        </a>
                    </div>
                </div>
            `;

            destinationGrid.appendChild(card);

            const description = card.querySelector(".destination-description");
            const showDescription = card.querySelector(".show-description");

            if (description.scrollHeight <= description.clientHeight) {
                showDescription.classList.add("d-none");
            }
            showDescription.addEventListener("click", function () {

                if (description.style.webkitLineClamp === "3") {

                    description.style.webkitLineClamp = "unset";
                    description.style.display = "block";
                    this.textContent = "Show less";

                } else {

                    description.style.display = "-webkit-box";
                    description.style.webkitLineClamp = "3";
                    this.textContent = "Show more";

                }

            });

        });

        filterDestinations();

    }

    function filterDestinations() {

        const searchValue = destinationSearch.value.toLowerCase().trim();
        let visibleCount = 0;

        destinationGrid.querySelectorAll(".destination-item").forEach(function (item) {

            const name = item.dataset.name.toLowerCase();
            const category = item.dataset.category.toLowerCase();

            const matchesSearch = name.includes(searchValue);
            const matchesCategory = activeFilter === "all" || category === activeFilter;

            if (matchesSearch && matchesCategory) {
                item.classList.remove("d-none");
                visibleCount++;
            } else {
                item.classList.add("d-none");
            }

        });

        destinationCount.textContent = visibleCount + " destinations";
        noDestinations.classList.toggle("d-none", visibleCount !== 0);

    }

    destinationSearch.addEventListener("input", filterDestinations);

        destinationFilters.addEventListener("click", function (event) {

        const button = event.target.closest("[data-filter]");

        if (!button) return;

        activeFilter = button.dataset.filter;

        destinationFilters.querySelectorAll("[data-filter]").forEach(function (filterButton) {
            filterButton.classList.remove("active", "text-white");
            filterButton.style.backgroundColor = "#FFFFFF";
            filterButton.style.borderColor = "#38BDF8";
            filterButton.style.color = "#0284C7";
        });

        button.classList.add("active", "text-white");
        button.style.backgroundColor = "#38BDF8";
        button.style.borderColor = "#38BDF8";
        button.style.color = "#FFFFFF";

        filterDestinations();

    });

    loadDestinations();

});
