<?php
// pilot/metar_proxy.php
// Serverseitiger METAR-Abruf — umgeht Browser CORS-Beschränkungen

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$icao = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_GET['icao'] ?? ''));

if (strlen($icao) < 3 || strlen($icao) > 4) {
    http_response_code(400);
    echo json_encode(['error' => 'Ungültiger ICAO-Code']);
    exit;
}

$url = 'https://aviationweather.gov/api/data/metar?ids=' . urlencode($icao) . '&format=raw&hours=2&taf=false';

$ctx = stream_context_create([
    'http' => [
        'timeout'         => 8,
        'follow_location' => true,
        'user_agent'      => 'Mozilla/5.0 zingg.co METAR proxy',
    ],
    'ssl' => [
        'verify_peer'      => true,
        'verify_peer_name' => true,
    ]
]);

$result = @file_get_contents($url, false, $ctx);

if ($result === false) {
    http_response_code(502);
    echo json_encode(['error' => 'METAR-Quelle nicht erreichbar']);
    exit;
}

$text = trim($result);
if ($text === '') {
    echo json_encode(['metar' => '', 'found' => false]);
} else {
    echo json_encode(['metar' => $text, 'found' => true]);
}
