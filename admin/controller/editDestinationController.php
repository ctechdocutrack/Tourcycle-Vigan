<?php
session_start();

require_once '../../config/dbconn.php';

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
function uploadImage($file, $upload_directory, &$uploaded_files) {
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 20 * 1024 * 1024) {
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

    $filename = bin2hex(random_bytes(8)) . '.' . $allowed_types[$image_info[2]];
    $destination = $upload_directory . $filename;

    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception('Failed to upload image.');
    }

    $uploaded_files[] = $destination;

    return [
        'path' => 'uploads/destinations/' . $filename,
        'hash' => $hash
    ];
}

$transactionStarted = false;

try {

    // Upload Directories
    $uploaded_files = [];
    $upload_directory = '../uploads/destinations/';
    $audio_upload_directory = '../uploads/audio/';

    // Create Upload Directories if Needed
    if (!is_dir($upload_directory) && !mkdir($upload_directory, 0755, true)) {
        throw new Exception('Unable to create image upload directory.');
    }

    if (!is_dir($audio_upload_directory) && !mkdir($audio_upload_directory, 0755, true)) {
        throw new Exception('Unable to create audio upload directory.');
    }

    // Store Old File Paths
    $oldMainImagePath = $destination['image'];
    $oldAudioEnPath = $destination['audio_en'];
    $oldAudioTlPath = $destination['audio_tl'];
    $oldAudioIloPath = $destination['audio_ilo'];
    // Keep Existing Main Image
    $mainImagePath = $destination['image'];

    // Upload New Main Image if Selected
    if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {

        $newImage = uploadImage($_FILES['image'], $upload_directory, $uploaded_files);

        $mainImagePath = $newImage['path'];
    }

    $mainImagePath = mysqli_real_escape_string($conn, $mainImagePath);

    // Upload audio function
    function uploadAudio($file, $upload_directory, &$uploaded_files) {

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

        $filename = bin2hex(random_bytes(8)) . '.mp3';
        $destination = $upload_directory . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception('Failed to upload audio file.');
        }

        $uploaded_files[] = $destination;

        return 'uploads/audio/' . $filename;
    }

    // Keep Existing Audio Files
    $audioEnPath = $destination['audio_en'];
    $audioTlPath = $destination['audio_tl'];
    $audioIloPath = $destination['audio_ilo'];

    // Upload New English Audio if Selected
    if (isset($_FILES['audio_en']) && $_FILES['audio_en']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioEnPath = uploadAudio($_FILES['audio_en'], $audio_upload_directory, $uploaded_files);
    }

    // Upload New Tagalog Audio if Selected
    if (isset($_FILES['audio_tl']) && $_FILES['audio_tl']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioTlPath = uploadAudio($_FILES['audio_tl'], $audio_upload_directory, $uploaded_files);
    }

    // Upload New Ilokano Audio if Selected
    if (isset($_FILES['audio_ilo']) && $_FILES['audio_ilo']['error'] !== UPLOAD_ERR_NO_FILE) {
        $audioIloPath = uploadAudio($_FILES['audio_ilo'], $audio_upload_directory, $uploaded_files);
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

    // Delete Replaced Old Files
    $oldFiles = [
        [$oldMainImagePath, $mainImagePath, 'uploads/destinations/'],
        [$oldAudioEnPath, $audioEnPath, 'uploads/audio/'],
        [$oldAudioTlPath, $audioTlPath, 'uploads/audio/'],
        [$oldAudioIloPath, $audioIloPath, 'uploads/audio/']
    ];

    foreach ($oldFiles as $file) {

        $oldPath = (string) $file[0];
        $newPath = (string) $file[1];
        $folder = $file[2];

        if ($oldPath !== $newPath && strpos($oldPath, $folder) === 0) {

            $oldFile = __DIR__ . '/../' . $oldPath;

            if (is_file($oldFile)) {
                unlink($oldFile);
            }
        }
    }

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

            $additionalImage = uploadImage($additionalFile, $upload_directory, $uploaded_files);

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

    // Remove Newly Uploaded Files if Update Fails
    foreach ($uploaded_files as $file) {
        if (file_exists($file)) {
            unlink($file);
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