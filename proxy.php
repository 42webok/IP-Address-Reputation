<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // Allow requests from your frontend

$apiKey = '5fef6052508d9547b220620a3df90b6bf02db4d7743a0b646ecc4a84d18201c173b370fddbe26771'; // 🔒 Replace with your actual API key (store securely in production!)

// Get data from frontend
$ipAddress = $_POST['ipAddress'] ?? '118.25.6.39'; // Default IP if none provided
$maxAgeInDays = $_POST['maxAgeInDays'] ?? '90';    // Default 90 days

// Prepare AbuseIPDB API request
$url = "https://api.abuseipdb.com/api/v2/check?" . http_build_query([
    'ipAddress' => $ipAddress,
    'maxAgeInDays' => $maxAgeInDays
]);

$options = [
    'http' => [
        'method' => 'GET',
        'header' => [
            'Accept: application/json',
            'Key: ' . $apiKey
        ]
    ]
];

// Fetch data from AbuseIPDB
$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);

if ($result === FALSE) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch data from AbuseIPDB']);
    exit;
}

// Return the API response to frontend
echo $result;
?>