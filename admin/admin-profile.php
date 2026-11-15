<?php
include 'includes/admin-auth.php';
include 'includes/admin-header.php';
include 'includes/flash-alert.php';
include '../config/dbconn.php';

$adminId = (int) $_SESSION['admin_id'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrfToken = $_SESSION['csrf_token'];

$userQuery = mysqli_query($conn, "
    SELECT admin_id, username, role
    FROM admin
    WHERE admin_id = $adminId
    LIMIT 1
");

if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
    die('Unable to retrieve account information.');
}

$user = mysqli_fetch_assoc($userQuery);

if ($user['role'] === 'admin') {
    $driverQuery = mysqli_query($conn, "
        SELECT admin_id, username, qr_token
        FROM admin
        WHERE role = 'driver'
        ORDER BY username ASC
    ");
} else {
    $driverQuery = mysqli_query($conn, "
        SELECT admin_id, username, qr_token
        FROM admin
        WHERE admin_id = $adminId AND role = 'driver'
        LIMIT 1
    ");
}
?>

<div class="container-fluid">
    <div class="row">

        <?php include 'includes/admin-sidebar.php'; ?>

        <main class="col-md-10 col-lg-10 px-3 px-md-4 py-4">

            
            <!-- Profile Header -->
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 mb-4">
                <div>
                    <h3 class="fw-bold mb-1">My Profile</h3>
                    <p class="text-muted mb-0">Manage your account and view your profile information.</p>
                </div>
                <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2 align-self-start">
                    <i class="bi bi-shield-check me-1"></i>
                    <?= htmlspecialchars(ucfirst($user['role'])) ?> Account
                </span>
            </div>

            <!-- Profile Overview -->
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="bg-success-subtle px-4 py-4">
                    <div class="d-flex flex-column flex-sm-row align-items-center align-items-sm-center gap-3">
                        <div class="rounded-circle bg-white shadow-sm d-flex align-items-center justify-content-center flex-shrink-0"
                            style="width: 76px; height: 76px;">
                            <i class="bi bi-person-fill text-success" style="font-size: 2.5rem;"></i>
                        </div>

                        <div class="text-center text-sm-start">
                            <h4 class="fw-bold mb-1 text-dark text-break">
                                <?= htmlspecialchars($user['username']) ?>
                            </h4>
                            <p class="text-muted mb-0">
                                <i class="bi bi-person-badge me-1"></i>
                                <?= htmlspecialchars(ucfirst($user['role'])) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3 p-md-4">
                    <h6 class="fw-bold mb-3">Account Information</h6>

                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="bg-body-tertiary rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-hash me-1"></i>Account ID
                                </small>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars($user['admin_id']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="bg-body-tertiary rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-person me-1"></i>Username
                                </small>
                                <div class="fw-semibold text-break">
                                    <?= htmlspecialchars($user['username']) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-sm-6">
                            <div class="bg-body-tertiary rounded-3 p-3 h-100">
                                <small class="text-muted d-block mb-2">
                                    <i class="bi bi-shield-check me-1"></i>Account Role
                                </small>
                                <div class="fw-semibold">
                                    <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php if ($user['role'] === 'admin'): ?>
                
                <!-- Account Management -->
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-person-plus fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Account Management</h5>
                                <p class="text-muted small mb-0">Create new Admin and Driver accounts.</p>
                            </div>
                        </div>

                        <form action="controller/createAccountController.php" method="POST">

                            <input type="hidden" name="csrf_token"
                                value="<?= htmlspecialchars($csrfToken) ?>">

                            <div class="row g-3">

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Username</label>
                                    <input type="text" name="username" class="form-control form-control-lg"
                                        placeholder="Enter username" maxlength="50" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Account Role</label>
                                    <select name="role" class="form-select form-select-lg" required>
                                        <option value="" selected disabled>Select account role</option>
                                        <option value="admin">Admin</option>
                                        <option value="driver">Tourcycle Driver</option>
                                    </select>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">Temporary Password</label>
                                    <div class="input-group input-group-lg">
                                        <input type="text" name="password" id="generatedPassword"
                                            class="form-control" placeholder="Generate 6-digit password"
                                            inputmode="numeric" maxlength="6" pattern="[0-9]{6}" required>

                                        <button type="button" class="btn btn-outline-success"
                                            id="generatePassword">
                                            <i class="bi bi-shuffle me-1"></i> Generate
                                        </button>
                                    </div>
                                    <small class="text-muted">Generate a six-digit temporary password.</small>
                                </div>

                                <div class="col-12">
                                    <hr class="my-2">
                                    <button type="submit" name="createAccount" class="btn btn-success px-4 py-2 w-100 w-md-auto">
                                        <i class="bi bi-person-plus me-2"></i>Create Account
                                    </button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>

            <?php endif; ?>

            <?php if (in_array($user['role'], ['admin', 'driver'], true)): ?>
                 
                <!-- Driver QR Codes -->
                <div class="card border-0 shadow-sm rounded-4 mt-4">
                    <div class="card-body p-3 p-md-4">

                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-3 bg-success-subtle text-success d-flex align-items-center justify-content-center flex-shrink-0"
                                style="width: 48px; height: 48px;">
                                <i class="bi bi-qr-code fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">Driver QR Codes</h5>
                                <p class="text-muted small mb-0">Unique QR codes for visitor access assigned to each Driver.</p>
                            </div>
                        </div>

                        <div class="row g-3">

                            <?php if ($driverQuery && mysqli_num_rows($driverQuery) > 0): ?>

                                <?php while ($driver = mysqli_fetch_assoc($driverQuery)): ?>

                                    <div class="col-12 col-sm-6 col-xl-4">
                                        <div class="border rounded-4 p-3 p-md-4 text-center h-100">

                                            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                                <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center"
                                                    style="width: 38px; height: 38px;">
                                                    <i class="bi bi-person-fill"></i>
                                                </div>
                                                <div class="text-start">
                                                    <h6 class="fw-bold mb-0 text-break">
                                                        <?= htmlspecialchars($driver['username']) ?>
                                                    </h6>
                                                    <small class="text-muted">
                                                        Driver ID: <?= (int) $driver['admin_id'] ?>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="bg-body-tertiary rounded-3 p-3 mb-3">
                                                <div class="d-flex justify-content-center">
                                                    <div
                                                        id="driverQr<?= (int) $driver['admin_id'] ?>"
                                                        class="driver-qr-code"
                                                        data-token="<?= htmlspecialchars($driver['qr_token'] ?? '') ?>">
                                                    </div>
                                                </div>
                                            </div>

                                            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                                                <i class="bi bi-check-circle me-1"></i>Unique Driver QR
                                            </span>

                                        </div>
                                    </div>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <div class="col-12">
                                    <div class="text-center py-5">
                                        <i class="bi bi-qr-code text-muted" style="font-size: 2.5rem;"></i>
                                        <h6 class="fw-bold mt-3 mb-1">No Driver Accounts Yet</h6>
                                        <p class="text-muted small mb-0">Create a Driver account to generate its QR code.</p>
                                    </div>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>


            <?php endif; ?>

        </main>
        
    </div>
</div>
<script src="../assets/js/qrcode.min.js"></script>
<script>
const generatePasswordButton = document.getElementById('generatePassword');

if (generatePasswordButton) {
    generatePasswordButton.addEventListener('click', function () {
        const password = String(
            crypto.getRandomValues(new Uint32Array(1))[0] % 900000 + 100000
        );

        document.getElementById('generatedPassword').value = password;
    });
}
// Generate QR Codes for Drivers
document.querySelectorAll('.driver-qr-code').forEach(function (element) {
    const token = element.dataset.token;

    if (!token) {
        element.innerHTML = '<small class="text-danger">QR token unavailable</small>';
        return;
    }

    const driverUrl = window.location.origin +
    '/?driver=' + encodeURIComponent(token);

    new QRCode(element, {
        text: driverUrl,
        width: 180,
        height: 180,
        correctLevel: QRCode.CorrectLevel.H
    });
});
</script>

<?php include 'includes/admin-footer.php'; ?>
