<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/admin-header.php';
include 'includes/flash-alert.php';
?>

<div class="container-fluid min-vh-100">
    <div class="row min-vh-100">

        <!-- Branding Section -->
        <div class="col-md-6 d-none d-md-flex flex-column justify-content-between bg-success text-white p-5">

            <!-- Brand -->
            <div class="d-flex align-items-center gap-3">
                <div class="text-success rounded-3">
                    <img src="../assets/images/Tourcycle Vigan.png"
                        alt="Tourcycle Vigan Logo"
                        class="img-fluid"
                        style="width: 70px; height: 70px;">
                </div>

                <div>
                    <h5 class="fw-bold mb-0">TOURCYCLE VIGAN</h5>
                    <small class="text-white-50">DIGITAL TOUR GUIDE</small>
                </div>
            </div>

            <!-- Main Content -->
            <div class="my-3">
                <img src="../assets/images/calle-crisologo.jpg"
                    alt="Calle Crisologo"
                    class="img-fluid rounded-4 shadow mb-4 w-100 object-fit-cover"
                    style="height: 300px;">

                <h1 class="display-5 fw-bold">
                    Welcome Back.
                </h1>

                <p class="text-white-50 fs-5">
                    Manage destinations, locations, and the digital tourism experience of Vigan City.
                </p>
            </div>

            <!-- Footer -->
            <div>
                <small class="text-white-50">
                    © 2026 Tourcycle Vigan. All rights reserved.
                </small>
            </div>

        </div>

        <!-- Login Section -->
        <div class="col-12 col-md-6 d-flex align-items-center justify-content-center p-4 p-md-5">

            <div class="w-100" style="max-width: 420px;">

                <!-- Mobile Brand -->
                <div class="d-md-none text-center mb-3">
                    <div class="rounded-3 d-inline-flex p-2 mb-2">
                        <img src="../assets/images/Tourcycle Vigan.png"
                            alt="Tourcycle Vigan Logo"
                            class="img-fluid"
                            style="width: 70px; height: 70px;">
                    </div>

                    <h5 class="fw-bold text-success mb-0">
                        TOURCYCLE VIGAN
                    </h5>

                    <small class="text-muted">DIGITAL TOUR GUIDE</small>
                </div>

                <!-- Login Heading -->
                <div class="mb-4">
                    <h2 class="fw-bold text-dark">Admin Sign In</h2>

                    <p class="text-muted">
                        Enter your credentials to access the dashboard.
                    </p>
                </div>

                <!-- Login Form -->
                <form action="controller/adminLoginController.php" method="POST" class="needs-validation" novalidate>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label fw-semibold">
                            Username
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-person text-secondary"></i>
                            </span>

                            <input type="text"
                                id="username"
                                name="username"
                                class="form-control shadow-none"
                                placeholder="Enter your username"
                                required>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-4">
                        <label for="password" class="form-label fw-semibold">
                            Password
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-lock text-secondary"></i>
                            </span>

                            <input type="password"
                                id="password"
                                name="password"
                                class="form-control shadow-none"
                                placeholder="Enter your password"
                                required>

                            <button type="button"
                                class="btn btn-outline-secondary"
                                id="togglePassword">
                                <i class="bi bi-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Sign In -->
                    <button type="submit" name="adminLogin" class="btn btn-success w-100 py-3 fw-semibold">
                        Sign In
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>

                </form>

                <!-- Footer -->
                <div class="text-center mt-5">
                    <small class="text-muted">
                        © 2026 Tourcycle Vigan. All rights reserved.
                    </small>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- Password Toggle -->
<script>
    document.getElementById('togglePassword').addEventListener('click', function () {
        const password = document.getElementById('password');
        const icon = document.getElementById('passwordIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.classList.replace('bi-eye', 'bi-eye-slash');
        } else {
            password.type = 'password';
            icon.classList.replace('bi-eye-slash', 'bi-eye');
        }
    });
</script>

<?php include 'includes/admin-footer.php'; ?>