<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $addresses = json_decode($_POST['addresses']);
    $apiKey = "AIzaSyDLEYS7vn5FTgmGoHl7-5kdWcCE62CMhc8";

    $totalDistance = 0;

    for ($i = 0; $i < count($addresses) - 1; $i++) {
        $origin = urlencode($addresses[$i]);
        $destination = urlencode($addresses[$i + 1]);

        $url = "https://maps.googleapis.com/maps/api/directions/json?origin=$origin&destination=$destination&key=$apiKey&mode=truck";

        $response = file_get_contents($url);
        $data = json_decode($response, true);

        if (!empty($data['routes'][0]['legs'][0]['distance'])) {
            $distance = $data['routes'][0]['legs'][0]['distance']['value']; // Get the distance in meters
            $totalDistance += $distance;
        }
    }

    echo $totalDistance * 0.000621371; // Convert the total distance to miles and output it
}