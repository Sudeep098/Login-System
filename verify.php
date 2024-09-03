<?php
session_start(); // Start the session

// Disable HTML error output and enable logging
ini_set('display_errors', 0); // Don't display errors on the output
ini_set('log_errors', 1); // Log errors to the server log
error_reporting(E_ALL); // Report all types of errors

// Set CORS headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Content-Type: application/json"); // Ensure response is JSON

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

// Ensure POST data is available
if (!isset($_POST['CC']) || !isset($_POST['mobile']) || !isset($_POST['otp'])) {
    echo json_encode(['status' => 'error', 'message' => 'Form data not received.']);
    exit;
}

$country_code = $_POST['CC'];
$mobile_number = $_POST['mobile'];
$otp = $_POST['otp'];

// Data to send in the POST request
$postData = [
    "country_code" => $country_code,
    "mobile_number" => $mobile_number,
    "otp" => $otp
];
// Convert the data to JSON format
$jsonData = json_encode($postData);

// Target URL
$url = "http://185.193.19.48:9080/api/v1/verify-otp";

// Initialize cURL session
$ch = curl_init($url);

// Set cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'device-type: Mobile',
    'description: MOBILE WEB'
]);

// Disable SSL verification (not recommended for production)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

// Execute the cURL request
$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);

// Check for errors
if ($response === false) {
    echo json_encode(['status' => 'error', 'message' => 'cURL Error: ' . $curlError]);
    exit;
}

// Decode JSON response
$responseData = json_decode($response, true);

if (json_last_error() === JSON_ERROR_NONE) {
    // Return the decoded response
    echo json_encode($responseData);
} else {
    // Handle JSON decoding error
    echo json_encode(['status' => 'error', 'message' => 'JSON Decode Error: ' . json_last_error_msg()]);
}
?>
