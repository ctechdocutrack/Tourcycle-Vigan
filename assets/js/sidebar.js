
// ==========================================
// SIDEBAR TOGGLE
// ==========================================

const mainSidebar = document.getElementById("mainSidebar");
const sidebarToggle = document.getElementById("sidebarToggle");
const sidebarTexts = document.querySelectorAll(".sidebar-text");
const sidebarLinks = document.querySelectorAll(".sidebar-link");
const sidebarBrand = document.getElementById("sidebarBrand");
const mapSection = document.getElementById("map");

let sidebarExpanded = false;

function updateSidebar() {

    const isDesktop = window.innerWidth >= 992;

    if (isDesktop) {

        const sidebarWidth = sidebarExpanded ? "260px" : "75px";

        mainSidebar.style.width = sidebarWidth;
        mapSection.style.marginLeft = sidebarWidth;

        sidebarTexts.forEach(text => {
            text.classList.toggle("d-none", !sidebarExpanded);
        });

        sidebarLinks.forEach(link => {
            link.classList.toggle("text-center", !sidebarExpanded);
            link.classList.toggle("px-0", !sidebarExpanded);
        });

        sidebarBrand.classList.toggle("d-none", !sidebarExpanded);

        sidebarToggle.classList.toggle("me-3", sidebarExpanded);
        sidebarToggle.classList.toggle("mx-auto", !sidebarExpanded);

    } else {

        mapSection.style.marginLeft = "0px";

    }

    setTimeout(() => {
        if (typeof touristMap !== "undefined") {
            touristMap.invalidateSize();
        }
    }, 350);
}

// Initial Sidebar State
updateSidebar();

// Toggle Sidebar
sidebarToggle.addEventListener("click", function () {
    sidebarExpanded = !sidebarExpanded;
    updateSidebar();
});

// Update layout when screen size changes
window.addEventListener("resize", updateSidebar);


// ==========================================
// MOBILE LOCATION BUTTON
// ==========================================

document.getElementById("mobileLocateMe").addEventListener("click", function () {

    document.getElementById("locateMeMap").click();

});
