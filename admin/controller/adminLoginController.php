
<?php
session_start();
include "../../config/dbconn.php";

if (isset($_POST['adminLogin'])) {

    $username = mysqli_real_escape_string($conn, trim($_POST['username']));
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $query = "SELECT * FROM admin WHERE username = '$username' AND password = '$password' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $admin = mysqli_fetch_assoc($result);

        session_regenerate_id(true);

        $_SESSION['admin_id'] = $admin['admin_id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_role'] = $admin['role'];

        // Fetch all destinations
        $destinationQuery = "SELECT * FROM destinations";
        $destinationResult = mysqli_query($conn, $destinationQuery);

        $_SESSION['destinations'] = [];

        if ($destinationResult) {
            $_SESSION['destinations'] = mysqli_fetch_all($destinationResult, MYSQLI_ASSOC);
        }

        // Cache additional destination images
        $_SESSION['additional_images'] = [];

        $imageQuery = "SELECT destination_id, image FROM destination_images";
        $imageResult = mysqli_query($conn, $imageQuery);

        if ($imageResult) {

            while ($image = mysqli_fetch_assoc($imageResult)) {

                $destinationId = $image['destination_id'];

                $_SESSION['additional_images'][$destinationId][] = $image['image'];
            }
        }

        // Cache destination locations
        $_SESSION['destination_locations'] = [];

        $destinations = $_SESSION['destinations'];

        reset($destinations);

        while ($row = current($destinations)) {

            $latitude = $row['latitude'];
            $longitude = $row['longitude'];

            $locationKey = $latitude . '_' . $longitude;

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

            $_SESSION['destination_locations'][$locationKey] = $location;

            next($destinations);
        }


        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Logged In Success!',
            'message' => 'Admin Successfully Loged In.'
        ];
        if ($admin['role'] === 'admin') {
            header("Location: ../dashboard.php");
        } else {
            header("Location: ../access-management.php");
        }
        exit();

    } else {

        $_SESSION['alert'] = [
            'type' => 'success',
            'title' => 'Logged In Failed!',
            'message' => 'Incorrect Username or Password.'
        ];
        header("Location: ../index.php");
        exit();
    }
}
?>
