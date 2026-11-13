
document.addEventListener("DOMContentLoaded", function () {

    const destinationSearch = document.getElementById("destinationSearch");
    const destinationFilters = document.getElementById("destinationFilters");
    const destinationItems = document.querySelectorAll(".destination-item");
    const destinationCount = document.getElementById("destinationCount");
    const noDestinations = document.getElementById("noDestinations");

    let activeFilter = "all";

    function filterDestinations() {

        const searchValue = destinationSearch.value.toLowerCase().trim();
        let visibleCount = 0;

        destinationItems.forEach(function (item) {

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

    destinationFilters.querySelectorAll("[data-filter]").forEach(function (button) {

        button.addEventListener("click", function () {

            activeFilter = this.dataset.filter;

            destinationFilters.querySelectorAll("[data-filter]").forEach(function (filterButton) {
                filterButton.classList.remove("btn-success", "active");
                filterButton.classList.add("btn-outline-success");
            });

            this.classList.remove("btn-outline-success");
            this.classList.add("btn-success", "active");

            filterDestinations();

        });

    });

    filterDestinations();

});
