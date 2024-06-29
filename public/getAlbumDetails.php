<?php
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $albumId = $_GET['id'];
    $url = "https://musicbrainz.org/ws/2/release-group/$albumId?inc=artists+releases&fmt=json";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'User-Agent: YourAppName/1.0 ( yourname@example.com )'
    ));
    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode == 200) {
        echo $response;
    } else {
        error_log("MusicBrainz API Error: HTTP $httpcode - $response");
        echo json_encode(['error' => 'Failed to fetch data from MusicBrainz API', 'status_code' => $httpcode]);
    }
} else {
    error_log("Error: No album ID provided");
    echo json_encode(['error' => 'No album ID provided']);
}
?>
