<?php
// Headers to allow CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// If it's an OPTIONS request, just return with CORS headers
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Configuration
$apiHost = '10.1.129.46:5001';
$endpoint = '/api/military-personnel'; // Adjust this endpoint based on your API
$url = "http://$apiHost$endpoint";

// Initialize cURL
$ch = curl_init();

// Configure cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

// Execute request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Check for errors
if (curl_errno($ch)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error communicating with API: ' . curl_error($ch)
    ]);
    exit();
}

// Close cURL
curl_close($ch);

// Set HTTP status code
http_response_code($httpCode);

// Set content type header
header('Content-Type: application/json');

// Return the response
echo $response;
?>