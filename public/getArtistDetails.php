<?php
header('Content-Type: application/json');

if (isset($_GET['name'])) {
    $artistName = urlencode($_GET['name']);
    $url = "https://musicbrainz.org/ws/2/artist/?query=artist:\"$artistName\"&fmt=json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: YourAppName/1.0 (yourname@example.com)'
    ));
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        $data = json_decode($response, true);
        if (isset($data['artists']) && count($data['artists']) > 0) {
            echo json_encode($data);
        } else {
            echo json_encode(['error' => 'No artist found']);
        }
    } else {
        error_log("MusicBrainz API Error: HTTP $httpcode - $response");
        echo json_encode(['error' => 'Failed to fetch data from MusicBrainz API', 'status_code' => $httpcode]);
    }
} else {
    error_log("Error: No artist name provided");
    echo json_encode(['error' => 'No artist name provided']);
}
?>
