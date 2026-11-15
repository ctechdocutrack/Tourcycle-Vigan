<?php
require_once '../includes/admin-auth.php';

if (($_SESSION['admin_role'] ?? '') !== 'admin') {
    header('Location: ../access-management.php');
    exit();
}

require_once '../../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $destination_name = mysqli_real_escape_string($conn, trim($_POST['destination_name'] ?? ''));
    $category = mysqli_real_escape_string($conn, trim($_POST['category'] ?? ''));
    $description = mysqli_real_escape_string($conn, trim($_POST['description'] ?? ''));
    $latitude = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? 'Active');

    $uploaded_files = [];
    $upload_directory = '../uploads/destinations/';
    $audio_upload_directory = '../uploads/audio/';

    try {
        // Validate destination information
        if (
            $destination_name === '' ||
            $description === '' ||
            !is_numeric($latitude) ||
            !is_numeric($longitude) ||
            !in_array($status, ['Active', 'Inactive'], true)
        ) {
            throw new Exception('Please complete all required fields and select a valid map location.');
        }

        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Please upload the main destination image.');
        }

        // Create upload directory if it does not exist
        if (!is_dir($upload_directory) && !mkdir($upload_directory, 0755, true)) {
            throw new Exception('Unable to create image upload directory.');
        }

        if (!is_dir($audio_upload_directory) && !mkdir($audio_upload_directory, 0755, true)) {
            throw new Exception('Unable to create audio upload directory.');
        }

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

        // Upload main image
        $main_image = uploadImage($_FILES['image'], $upload_directory, $uploaded_files);
        // Upload English audio
        $audio_en_path = uploadAudio($_FILES['audio_en'] ?? null, $audio_upload_directory, $uploaded_files);
        $audio_en_path = mysqli_real_escape_string($conn, $audio_en_path);
        // Upload Tagalog audio
        $audio_tl_path = uploadAudio($_FILES['audio_tl'] ?? null, $audio_upload_directory, $uploaded_files);
        $audio_tl_path = mysqli_real_escape_string($conn, $audio_tl_path);
        // Upload Ilokano audio
        $audio_ilo_path = uploadAudio($_FILES['audio_ilo'] ?? null, $audio_upload_directory, $uploaded_files);
        $audio_ilo_path = mysqli_real_escape_string($conn, $audio_ilo_path);
        
        $image_hashes = [$main_image['hash']];

        $main_image_path = mysqli_real_escape_string($conn, $main_image['path']);

        // Start database transaction
        mysqli_begin_transaction($conn);

        // Insert destination
        $sql = "INSERT INTO destinations 
        (destination_name, category, description, latitude, longitude, image, status, audio_en, audio_tl, audio_ilo)
        VALUES (
            '$destination_name',
            '$category',
            '$description',
            '$latitude',
            '$longitude',
            '$main_image_path',
            '$status',
            '$audio_en_path',
            '$audio_tl_path',
            '$audio_ilo_path'
        )";

        if (!mysqli_query($conn, $sql)) {
            throw new Exception('Failed to save destination: ' . mysqli_error($conn));
        }

        $destination_id = mysqli_insert_id($conn);

        // Upload and save additional images
        if (isset($_FILES['additional_images'])) {
            $files = $_FILES['additional_images'];
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $additional_file = [
                    'name' => $files['name'][$i],
                    'tmp_name' => $files['tmp_name'][$i],
                    'error' => $files['error'][$i],
                    'size' => $files['size'][$i]
                ];
                $additional_image = uploadImage($additional_file, $upload_directory, $uploaded_files);
                if (in_array($additional_image['hash'], $image_hashes, true)) {
                    throw new Exception('Duplicate image detected. Please select different images.');
                }
                $image_hashes[] = $additional_image['hash'];
                $image_path = mysqli_real_escape_string($conn, $additional_image['path']);

                $image_sql = "INSERT INTO destination_images (destination_id, image)
                              VALUES ('$destination_id', '$image_path')";

                if (!mysqli_query($conn, $image_sql)) {
                    throw new Exception('Failed to save additional image: ' . mysqli_error($conn));
                }
            }
        }
        // Commit transaction
        mysqli_commit($conn);

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Destination Added Successfully!',
            'message' => 'The destination has been successfully saved.'
        ];
        header('Location: ../destinations.php');
        exit;
    } catch (Throwable $error) {
        error_log('Add Destination Error: ' . $error->getMessage());
        mysqli_rollback($conn);
        // Remove uploaded files if saving fails
        foreach ($uploaded_files as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }
        $_SESSION['alert'] = [
            'type' => 'error',
            'title' => 'Failed to Add Destination!',
            'message' => 'An error occurred while saving the destination.'
        ];
        header('Location: ../add-destination.php');
        exit;
    }
}
header('Location: ../add-destination.php');
exit;
?>
