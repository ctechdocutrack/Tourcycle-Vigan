
<?php include 'assets/includes/header.php'; ?>
<?php include 'assets/includes/sidebar.php'; ?>

<section id="map" class="position-relative min-vh-100 px-4 px-lg-5 py-4">

    <div class="mb-4">
        <span class="text-danger fw-semibold text-uppercase small">Stay Safe</span>
        <h2 class="fw-bold mt-1">Emergency Contacts</h2>
        <p class="text-muted mb-0">Important contact numbers for visitors and tourists in Vigan City.</p>
    </div>

    <!-- National Emergency -->
    <div class="card border-0 shadow-sm rounded-4 bg-danger text-white mb-4">
        <div class="card-body p-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-white text-danger mb-2">24/7 Emergency Hotline</span>
                <h3 class="fw-bold mb-1">National Emergency Hotline</h3>
                <p class="mb-0 opacity-75">For police, fire, and medical emergencies nationwide.</p>
            </div>
            <div class="text-end">
                <h1 class="display-4 fw-bold mb-2">911</h1>
                <a href="tel:911" class="btn btn-light text-danger rounded-pill px-4 fw-semibold">
                    <i class="bi bi-telephone-fill me-2"></i>Call Now
                </a>
            </div>
        </div>
    </div>

    <div class="mb-3">
        <h4 class="fw-bold mb-1">Vigan City Emergency Contacts</h4>
        <p class="text-muted small mb-0">Local emergency and public service numbers.</p>
    </div>

    <div class="row g-3">

        <!-- Police -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary-subtle text-primary rounded-3 p-3">
                            <i class="bi bi-shield-fill fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Police Station</h5>
                            <span class="text-muted small">Police Assistance</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">166</h4>
                    <p class="text-muted small">Alternative: 722-0890</p>
                    <a href="tel:166" class="btn btn-outline-primary rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Police
                    </a>
                </div>
            </div>
        </div>

        <!-- Fire -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-danger-subtle text-danger rounded-3 p-3">
                            <i class="bi bi-fire fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Fire Station</h5>
                            <span class="text-muted small">Fire and Rescue</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">1600</h4>
                    <p class="text-muted small">Alternative: 722-0990</p>
                    <a href="tel:1600" class="btn btn-outline-danger rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Fire Station
                    </a>
                </div>
            </div>
        </div>

        <!-- Hospital -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-success-subtle text-success rounded-3 p-3">
                            <i class="bi bi-hospital fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Gabriela Silang Hospital</h5>
                            <span class="text-muted small">Medical Assistance</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">722-3362</h4>
                    <p class="text-muted small">Alternative: 722-2781</p>
                    <a href="tel:07223362" class="btn btn-outline-success rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Hospital
                    </a>
                </div>
            </div>
        </div>

        <!-- Public Safety -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-warning-subtle text-warning rounded-3 p-3">
                            <i class="bi bi-cone-striped fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Public Safety</h5>
                            <span class="text-muted small">Public Safety Assistance</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">711</h4>
                    <p class="text-muted small">Vigan City Public Safety</p>
                    <a href="tel:711" class="btn btn-outline-warning rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Public Safety
                    </a>
                </div>
            </div>
        </div>

        <!-- Health Office -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-info-subtle text-info rounded-3 p-3">
                            <i class="bi bi-heart-pulse fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">City Health Office</h5>
                            <span class="text-muted small">Health Services</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">722-6785</h4>
                    <p class="text-muted small">Vigan City Health Office</p>
                    <a href="tel:07226785" class="btn btn-outline-info rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Health Office
                    </a>
                </div>
            </div>
        </div>

        <!-- Mayor's Office -->
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-secondary-subtle text-secondary rounded-3 p-3">
                            <i class="bi bi-building fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-0">Mayor's Office</h5>
                            <span class="text-muted small">City Government</span>
                        </div>
                    </div>
                    <h4 class="fw-bold">722-2466</h4>
                    <p class="text-muted small">Vigan City Hall</p>
                    <a href="tel:07222466" class="btn btn-outline-secondary rounded-pill w-100">
                        <i class="bi bi-telephone me-2"></i>Call Mayor's Office
                    </a>
                </div>
            </div>
        </div>

    </div>

    <div class="alert alert-warning border-0 rounded-4 mt-4">
        <div class="d-flex gap-3">
            <i class="bi bi-exclamation-triangle-fill fs-4"></i>
            <div>
                <h6 class="fw-bold mb-1">Important Reminder</h6>
                <p class="small mb-0">In case of an emergency, call 911. Local contact numbers shown here are from a published Vigan directory and should be verified with the City Government before this page is made public. Availability and response times may vary.</p>
            </div>
        </div>
    </div>

</section>

<script src="assets/js/sidebar.js"></script>

<?php include 'assets/includes/footer.php'; ?>
