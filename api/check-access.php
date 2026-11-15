<?php
include '../config/dbconn.php';

header('Content-Type: application/json');

$deviceToken = $_COOKIE['tour_device_token'] ?? '';

if (!$deviceToken) {
    echo json_encode(['status' => 'Not Found']);
    exit;
}

$tokenHash = hash('sha256', $deviceToken);
$tokenHash = mysqli_real_escape_string($conn, $tokenHash);

$result = mysqli_query($conn, "
    SELECT status
    FROM tour_sessions
    WHERE device_token_hash = '$tokenHash'
    LIMIT 1
");

if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['status' => 'Not Found']);
    exit;
}

$session = mysqli_fetch_assoc($result);

echo json_encode([
    'status' => $session['status']
]);
?>