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
    header("Location: ../destinations.php");
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

    header("Location: ../destinations.php");
    exit();
}

$destinationId = (int) $destinationId;

// Get Existing Destination
$query = "SELECT * FROM destinations WHERE destinations_id = $destinationId LIMIT 1";
$result = mysqli_query($conn, $query);

if (!$result) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Database Error!',
        'message' => 'Unable to retrieve destination information.'
    ];

    header("Location: ../destinations.php");
    exit();
}

$destination = mysqli_fetch_assoc($result);

if (!$destination) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Destination Not Found!',
        'message' => 'The selected destination does not exist.'
    ];

    header("Location: ../destinations.php");
    exit();
}

// Get Form Data
$destinationName = trim($_POST['destination_name'] ?? '');
$category = trim($_POST['category'] ?? '');
$description = trim($_POST['description'] ?? '');
$latitude = trim($_POST['latitude'] ?? '');
$longitude = trim($_POST['longitude'] ?? '');
$status = trim($_POST['status'] ?? '');

// Validate Required Fields
if (
    empty($destinationName) ||
    empty($category) ||
    empty($description) ||
    !is_numeric($latitude) ||
    !is_numeric($longitude) ||
    !in_array($status, ['Active', 'Inactive'], true)
) {
    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Invalid Information!',
        'message' => 'Please complete all required destination fields.'
    ];

    header("Location: ../edit-destinations.php?id=" . $destinationId);
    exit();
}

// Escape Form Data
$destinationName = mysqli_real_escape_string($conn, $destinationName);
$category = mysqli_real_escape_string($conn, $category);
$description = mysqli_real_escape_string($conn, $description);
$latitude = (float) $latitude;
$longitude = (float) $longitude;
$status = mysqli_real_escape_string($conn, $status);

// Upload image function
function uploadImage($file, &$uploaded_files) {

    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK || $file['size'] > 20 * 1024 * 1024) {
        throw new Exception('Each image must be valid and must not exceed 20 MB.');
    }

    $image_info = getimagesize($file['tmp_name']);

    if (!$image_info) {
        throw new Exception('Invalid image file.');
    }

    $allowed_types = [
        IMAGETYPE_JPEG => 'jpg',
        IMAGETYPE_PNG => 'png',
        IMAGETYPE_WEBP => 'webp'
    ];

    if (!isset($allowed_types[$image_info[2]])) {
        throw new Exception('Only JPG, PNG, and WEBP images are allowed.');
    }

    $hash = hash_file('sha256', $file['tmp_name']);

    $file['name'] = 'upload.' . $allowed_types[$image_info[2]];

    $github_upload = uploadToGitHub($file, 'uploads/destinations'); 

    $uploaded_files[] = [
        'path' => $github_upload['path'],
        'sha' => $github_upload['sha']
    ];

    return [
        'path' => $github_upload['url'],
        'hash' => $hash
    ];
}

$transactionStarted = false;

try {

    $uploaded_files = [];

    // Store Old File Paths
    $oldMainImagePath = $destination['image'];
    $oldAudioEnPath = $destination['audio_en'];
    $oldAudioTlPath = $destination['audio_tl'];
    $oldAudioIloPath = $destination['audio_ilo'];
    // Keep Existing Main Image
    $mainImagePath = $destination['image'];

    // Upload New Main Image if Selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        $newImage = uploadImage($_FILES['image'], $uploaded_files);

        $mainImagePath = $newImage['path'];
    }

    $mainImagePath = mysqli_real_escape_string($conn, $mainImagePath);

    // Upload audio function
    function uploadAudio($file, &$uploaded_files) {

        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Please upload the English audio file.');
        }

        if ($file['size'] > 20 * 1024 * 1024) {
            throw new Exception('Audio file must not exceed 20 MB.');
        }

        $file_info = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($file_info, $file['tmp_name']);
        finfo_close($file_info);

        if ($mime_type !== 'audio/mpeg') {
            throw new Exception('Only MP3 audio files are allowed.');
        }

        $file['name'] = 'upload.mp3';

        $github_upload = uploadToGitHub($file, 'uploads/audio');

        $uploaded_files[] = [
            'path' => $github_upload['path'],
            'sha' => $github_upload['sha']
        ];

        return $github_upload['url'];
    }

    // Keep Existing Audio Files
    $audioEnPath = $destination['audio_en'];
    $audioTlPath = $destination['audio_tl'];
    $audioIloPath = $destination['audio_ilo'];

    // Upload New English Audio if Selected
    if (isset($_FILES['audio_en']) && $_FILES['audio_en']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioEnPath = uploadAudio($_FILES['audio_en'], $uploaded_files);
    }

    // Upload New Tagalog Audio if Selected
    if (isset($_FILES['audio_tl']) && $_FILES['audio_tl']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioTlPath = uploadAudio($_FILES['audio_tl'], $uploaded_files);
    }

    // Upload New Ilokano Audio if Selected
    if (isset($_FILES['audio_ilo']) && $_FILES['audio_ilo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioIloPath = uploadAudio($_FILES['audio_ilo'], $uploaded_files);
    }

    // Escape Audio Paths
    $audioEnPath = mysqli_real_escape_string($conn, $audioEnPath);
    $audioTlPath = mysqli_real_escape_string($conn, $audioTlPath);
    $audioIloPath = mysqli_real_escape_string($conn, $audioIloPath);

    // Start Database Transaction
    if (!mysqli_begin_transaction($conn)) {
        throw new Exception('Failed to start database transaction.');
    }

    $transactionStarted = true;

    // Store Replaced Old Files for Deletion After Successful Update
    $oldFiles = [
        [$oldMainImagePath, $mainImagePath],
        [$oldAudioEnPath, $audioEnPath],
        [$oldAudioTlPath, $audioTlPath],
        [$oldAudioIloPath, $audioIloPath]
    ];

    // Update Destination
    $sql = "UPDATE destinations SET
        destination_name = '$destinationName',
        category = '$category',
        description = '$description',
        latitude = '$latitude',
        longitude = '$longitude',
        image = '$mainImagePath',
        status = '$status',
        audio_en = '$audioEnPath',
        audio_tl = '$audioTlPath',
        audio_ilo = '$audioIloPath'
        WHERE destinations_id = $destinationId";

    if (!mysqli_query($conn, $sql)) {
        throw new Exception('Failed to update destination: ' . mysqli_error($conn));
    }

    // Upload and Save Additional Images
    if (isset($_FILES['additional_images'])) {

        $files = $_FILES['additional_images'];

        for ($i = 0; $i < count($files['name']); $i++) {

            if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $additionalFile = [
                'name' => $files['name'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i]
            ];

            $additionalImage = uploadImage($additionalFile, $uploaded_files);

            $imagePath = mysqli_real_escape_string($conn, $additionalImage['path']);

            $imageSql = "INSERT INTO destination_images (destination_id, image)
                        VALUES ('$destinationId', '$imagePath')";

            if (!mysqli_query($conn, $imageSql)) {
                throw new Exception('Failed to save additional image: ' . mysqli_error($conn));
            }
        }
    }

    // Commit Database Transaction
    if (!mysqli_commit($conn)) {
        throw new Exception('Failed to commit database transaction.');
    }

    $transactionStarted = false;

    // Delete Replaced Old Files from GitHub or Local Storage
    foreach ($oldFiles as $file) {

        $oldPath = trim((string) $file[0]);
        $newPath = trim((string) $file[1]);

        if ($oldPath === '' || $oldPath === $newPath) {
            continue;
        }

        try {

            if (strpos($oldPath, 'https://raw.githubusercontent.com/') === 0) {

                deleteGitHubFileByUrl($oldPath);

            } elseif (
                strpos($oldPath, 'uploads/destinations/') === 0 ||
                strpos($oldPath, 'uploads/audio/') === 0
            ) {

                $oldFile = __DIR__ . '/../' . $oldPath;

                if (is_file($oldFile)) {
                    unlink($oldFile);
                }
            }

        } catch (Throwable $file_error) {
            error_log('Old destination file deletion failed: ' . $file_error->getMessage());
        }
    }

    // Refresh Destination Session Cache
    $updatedQuery = "SELECT * FROM destinations WHERE destinations_id = $destinationId LIMIT 1";
    $updatedResult = mysqli_query($conn, $updatedQuery);

    if ($updatedResult) {

        $updatedDestination = mysqli_fetch_assoc($updatedResult);

        // Update Destination Location Cache
        $oldLocationKey = $destination['latitude'] . '_' . $destination['longitude'];
        $newLocationKey = $updatedDestination['latitude'] . '_' . $updatedDestination['longitude'];

        if ($oldLocationKey !== $newLocationKey) {
            unset($_SESSION['destination_locations'][$oldLocationKey]);
        }
        if (!isset($_SESSION['destination_locations'][$newLocationKey])) {
            $latitude = $updatedDestination['latitude'];
            $longitude = $updatedDestination['longitude'];

            $location = "Location unavailable";

            $url = "https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=" . urlencode($latitude) . "&lon=" . urlencode($longitude);

            $context = stream_context_create([
                "http" => [
                    "header" => "User-Agent: TourcycleVigan/1.0\r\n",
                    "timeout" => 5
                ]
            ]);

            $response = @file_get_contents($url, false, $context);

            if ($response !== false) {

                $geoData = json_decode($response, true);

                if (!empty($geoData['address'])) {

                    $address = $geoData['address'];

                    $location = $address['city']
                        ?? $address['town']
                        ?? $address['village']
                        ?? $address['municipality']
                        ?? $address['county']
                        ?? "Location unavailable";

                    if (!empty($address['state'])) {
                        $location .= ", " . $address['state'];
                    }
                }
            }

            $_SESSION['destination_locations'][$newLocationKey] = $location;
        }

        foreach ($_SESSION['destinations'] ?? [] as $index => $row) {

            if ((int) $row['destinations_id'] === $destinationId) {
                $_SESSION['destinations'][$index] = $updatedDestination;
                break;
            }

        }

    }

    // Refresh Additional Images Session Cache
    $imageQuery = "SELECT image FROM destination_images WHERE destination_id = $destinationId";
    $imageResult = mysqli_query($conn, $imageQuery);

    if ($imageResult) {

        $_SESSION['additional_images'][$destinationId] = [];

        while ($image = mysqli_fetch_assoc($imageResult)) {
            $_SESSION['additional_images'][$destinationId][] = $image['image'];
        }

    }
    // Success Message
    $_SESSION['alert'] = [
        'type' => 'success',
        'title' => 'Destination Updated Successfully!',
        'message' => 'The destination has been successfully updated.'
    ];

    header('Location: ../destinations.php');
    exit;

} catch (Throwable $error) {

    // Rollback Database Changes
    if ($transactionStarted) {
        mysqli_rollback($conn);
    }

    // Remove Newly Uploaded Files from GitHub if Update Fails
    foreach ($uploaded_files as $file) {

        try {
            deleteFromGitHub($file['path'], $file['sha']);
        } catch (Throwable $cleanup_error) {
            error_log('GitHub cleanup failed: ' . $cleanup_error->getMessage());
        }
    }

    $_SESSION['alert'] = [
        'type' => 'error',
        'title' => 'Failed to Update Destination!',
        'message' => 'An error occurred while updating the destination.'
    ];

    header('Location: ../edit-destinations.php?id=' . $destinationId);
    exit;
}
?>