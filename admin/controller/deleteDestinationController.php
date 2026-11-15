<?php
require_once '../includes/admin-auth.php';

if (($_SESSION['admin_role'] ?? '') !== 'admin') {
    header('Location: ../access-management.php');
    exit();
}

require_once '../../config/dbconn.php';
require_once '../includes/github-upload.php';

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../destinations.php');
    exit();
}

// Get Destination ID
$destinationId = $_POST['destination_id'] ?? '';

if (!ctype_digit((string) $destinationId)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Invalid Destination!',
        'message' => 'Invalid destination ID.'
    ];

    header('Location: ../destinations.php');
    exit();
}

$destinationId = (int) $destinationId;

// Get Existing Destination
$query = "SELECT * FROM destinations WHERE destinations_id = $destinationId LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) === 0) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Destination Not Found!',
        'message' => 'The selected destination does not exist.'
    ];

    header('Location: ../destinations.php');
    exit();
}

$destination = mysqli_fetch_assoc($result);

// Get Additional Images
$imageQuery = "SELECT image FROM destination_images WHERE destination_id = $destinationId";
$imageResult = mysqli_query($conn, $imageQuery);

if (!$imageResult) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error!',
        'message' => 'Unable to retrieve destination images.'
    ];

    header('Location: ../destinations.php');
    exit();
}

$additionalImages = [];

while ($imageRow = mysqli_fetch_assoc($imageResult)) {
    $additionalImages[] = $imageRow['image'];
}

// Collect Destination Files
$filesToDelete = [
    $destination['image'],
    $destination['audio_en'],
    $destination['audio_tl'],
    $destination['audio_ilo']
];

// Add Additional Image Files
foreach ($additionalImages as $image) {
    $filesToDelete[] = $image;
}

// Start Database Transaction
if (!mysqli_begin_transaction($conn)) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error!',
        'message' => 'Failed to start database transaction.'
    ];

    header('Location: ../destinations.php');
    exit();
}

try {

    // Delete Additional Image Records
    $deleteImages = "DELETE FROM destination_images WHERE destination_id = $destinationId";

    if (!mysqli_query($conn, $deleteImages)) {
        throw new Exception('Failed to delete destination images.');
    }

    // Delete Destination Record
    $deleteDestination = "DELETE FROM destinations WHERE destinations_id = $destinationId";

    if (!mysqli_query($conn, $deleteDestination)) {
        throw new Exception('Failed to delete destination.');
    }

    // Commit Transaction
    if (!mysqli_commit($conn)) {
        throw new Exception('Failed to commit database transaction.');
    }

} catch (Throwable $error) {

    mysqli_rollback($conn);

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Deletion Failed!',
        'message' => 'Unable to delete the destination.'
    ];

    header('Location: ../destinations.php');
    exit();
}

// Delete Uploaded Files from GitHub
foreach ($filesToDelete as $filePath) {

    $filePath = trim((string) $filePath);

    if ($filePath === '') {
        continue;
    }

    try {
        if (strpos($filePath, 'https://raw.githubusercontent.com/') === 0) {
            deleteGitHubFileByUrl($filePath);
        } elseif (
            strpos($filePath, 'uploads/destinations/') === 0 ||
            strpos($filePath, 'uploads/audio/') === 0
        ) {
            // Remove legacy local files, if any
            $file = __DIR__ . '/../' . $filePath;

            if (is_file($file)) {
                unlink($file);
            }
        }
    } catch (Throwable $file_error) {
        error_log('Destination file deletion failed: ' . $file_error->getMessage());
    }
}

// Remove Destination from Session Cache
foreach ($_SESSION['destinations'] ?? [] as $index => $row) {

    if ((int) $row['destinations_id'] === $destinationId) {
        unset($_SESSION['destinations'][$index]);
        break;
    }
}

// Re-index Destination Array
$_SESSION['destinations'] = array_values($_SESSION['destinations'] ?? []);

// Remove Additional Images Cache
unset($_SESSION['additional_images'][$destinationId]);

// Remove Destination Location Cache
$locationKey = $destination['latitude'] . '_' . $destination['longitude'];
unset($_SESSION['destination_locations'][$locationKey]);

// Success Message
$_SESSION['alert'] = [
    'type' => 'success',
    'title' => 'Destination Deleted Successfully!',
    'message' => 'The destination and its associated files have been deleted.'
];

header('Location: ../destinations.php');
exit();