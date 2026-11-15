<?php
include '../includes/admin-auth.php';
include '../../config/dbconn.php';

// Only Admin can create accounts
if (($_SESSION['admin_role'] ?? '') !== 'admin') {
    header("Location: ../admin-profile.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['createAccount'])) {
    header("Location: ../admin-profile.php");
    exit();
}

// Validate CSRF token
$csrfToken = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    die('Invalid request. Please refresh the page and try again.');
}

// Get form values
$username = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? '';

// Validate inputs
if (
    $username === '' ||
    !in_array($role, ['admin', 'driver'], true) ||
    !preg_match('/^[0-9]{6}$/', $password)
) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'title' => 'Invalid Account Details',
        'message' => 'Please check the username, role, and six-digit password.'
    ];

    header("Location: ../admin-profile.php");
    exit();
}

// Check duplicate username
$username = mysqli_real_escape_string($conn, $username);

$checkUsername = mysqli_query($conn, "
    SELECT admin_id
    FROM admin
    WHERE username = '$username'
    LIMIT 1
");

if (!$checkUsername) {
    die('Unable to verify username. Please try again.');
}

if (mysqli_num_rows($checkUsername) > 0) {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'title' => 'Username Already Exists',
        'message' => 'Please choose another username.'
    ];

    header("Location: ../admin-profile.php");
    exit();
}

// Generate unique QR token for Driver accounts
$qrToken = $role === 'driver'
    ? bin2hex(random_bytes(32))
    : null;

$password = mysqli_real_escape_string($conn, $password);
$role = mysqli_real_escape_string($conn, $role);
$qrTokenValue = $qrToken
    ? "'" . mysqli_real_escape_string($conn, $qrToken) . "'"
    : "NULL";

// Create account
$insert = mysqli_query($conn, "
    INSERT INTO admin (username, password, role, qr_token)
    VALUES ('$username', '$password', '$role', $qrTokenValue)
");

if ($insert) {
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Account Created!',
        'message' => 'The new ' . ucfirst($role) . ' account was successfully created.'
    ];
} else {
    $_SESSION['alert'] = [
        'type' => 'danger',
        'title' => 'Account Creation Failed',
        'message' => 'Unable to create account. Please try again.'
    ];
}

header("Location: ../admin-profile.php");
exit();
?>
