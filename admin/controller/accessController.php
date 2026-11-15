<?php
include '../includes/admin-auth.php';
include '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../access-management.php");
    exit();
}

$csrfToken = $_POST['csrf_token'] ?? '';

if (
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $csrfToken)
) {
    die('Invalid request. Please refresh the page and try again.');
}

$tourSessionsId = (int) ($_POST['tour_sessions_id'] ?? 0);
$action = $_POST['action'] ?? '';

if ($tourSessionsId <= 0 || !in_array($action, ['activate', 'expire', 'deactivate', 'reactivate', 'end', 'restore'], true)) {
    header("Location: ../access-management.php");
    exit();
}

$requiredStatus = in_array($action, ['activate', 'expire'], true)
    ? 'Pending'
    : ($action === 'restore' ? 'Expired' : (in_array($action, ['deactivate', 'end'], true) ? 'Active' : 'Inactive'));

$newStatus = in_array($action, ['activate', 'reactivate'], true)
    ? 'Active'
    : ($action === 'expire' ? 'Expired' : ($action === 'end' ? 'Ended' : ($action === 'restore' ? 'Pending' : 'Inactive')));

$logAction = [
    'activate' => 'Access Activated',
    'expire' => 'Access Expired',
    'deactivate' => 'Access Deactivated',
    'reactivate' => 'Access Reactivated',
    'end' => 'Tour Session Ended',
    'restore' => 'Access Restored to Pending'
][$action];

mysqli_begin_transaction($conn);

$check = mysqli_query($conn, "
    SELECT status
    FROM tour_sessions
    WHERE tour_sessions_id = $tourSessionsId
    LIMIT 1
");

if ($check && mysqli_num_rows($check) > 0) {

    $session = mysqli_fetch_assoc($check);

    if ($session['status'] === $requiredStatus) {

        $timestampUpdate = '';

        if (in_array($action, ['activate', 'reactivate'], true)) {
            $timestampUpdate = ', activated_at = NOW()';
        } elseif ($action === 'expire') {
            $timestampUpdate = ', expired_at = NOW()';
        } elseif ($action === 'end') {
            $timestampUpdate = ', ended_at = NOW()';
        } elseif ($action === 'restore') {
            $timestampUpdate = ', expired_at = NULL';
        }

        $update = mysqli_query($conn, "
            UPDATE tour_sessions
            SET status = '$newStatus'
                $timestampUpdate
            WHERE tour_sessions_id = $tourSessionsId
            AND status = '$requiredStatus'
        ");

        if ($update && mysqli_affected_rows($conn) === 1) {

            $logAction = mysqli_real_escape_string($conn, $logAction);

            $log = mysqli_query($conn, "
                INSERT INTO tour_session_logs (
                    tour_sessions_id, action, previous_status, new_status
                ) VALUES (
                    $tourSessionsId, '$logAction', '$requiredStatus', '$newStatus'
                )
            ");

            if ($log) {
                mysqli_commit($conn);
            } else {
                mysqli_rollback($conn);
            }

        } else {
            mysqli_rollback($conn);
        }

    } else {
        mysqli_rollback($conn);
    }

} else {
    mysqli_rollback($conn);
}

header("Location: ../access-management.php");
exit();
?>