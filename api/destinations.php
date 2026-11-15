<?php
header('Content-Type: application/json; charset=utf-8');

include '../config/dbconn.php';

$query = "SELECT destinations_id, destination_name, category, description, latitude, longitude, image, audio_en, audio_tl, audio_ilo
          FROM destinations
          WHERE status = 'active'
          ORDER BY destination_name ASC";

$result = mysqli_query($conn, $query);

if (!$result) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to retrieve destinations.'
    ]);
    exit;
}

$destinations = [];

while ($row = mysqli_fetch_assoc($result)) {
    $destinations[] = [
        'id' => $row['destinations_id'],
        'name' => $row['destination_name'],
        'category' => $row['category'],
        'description' => $row['description'],
        'coordinates' => [
            (float) $row['latitude'],
            (float) $row['longitude']
        ],
        'image' => $row['image'],
        'audio' => [
            'en' => $row['audio_en'],
            'tl' => $row['audio_tl'],
            'ilo' => $row['audio_ilo']
        ]
    ];
}

echo json_encode([
    'success' => true,
    'data' => $destinations
]);
